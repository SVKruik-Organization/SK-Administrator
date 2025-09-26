import { Pool } from "mariadb/*";
import { Module, Profile, ProfileData, TopLink } from "~/assets/customTypes";

export async function getProfileData(userId: number, profileId: number, connection: Pool): Promise<ProfileData> {
    // Retrieve the user profile
    const rawUserProfiles: Array<Profile> = await connection.query("SELECT id, name, description, position, date_last_usage FROM user_profile WHERE user_id = ? ORDER BY date_last_usage, position;", [userId]);
    const activeProfile = rawUserProfiles[0];
    const userProfiles = rawUserProfiles.sort((a, b) => a.position - b.position);
    let chosenProfileId = profileId === 0 ? activeProfile.id : profileId;

    // Retrieve the modules and module items from the database
    const moduleData: Array<{
        "module_id": number,
        "module_name": string | null,
        "module_icon": string | null,
        "module_item_name": string,
        "module_item_icon": string | null,
    }> = await connection.query(`
            SELECT
                IFNULL(module.id, 0) as module_id,
                module.name AS module_name,
                module.icon AS module_icon,
                module_item.name AS module_item_name,
                module_item.icon AS module_item_icon
            FROM
                module
                LEFT JOIN user_profile_module ON module_id = module.id
                LEFT JOIN user_profile ON user_profile.id = user_profile_module.user_profile_id
                RIGHT JOIN module_item ON module_item.module_id = module.id
            WHERE user_profile.id = ? AND user_profile.user_id = ? OR module_item.module_id IS NULL;`, [chosenProfileId, userId]);
    const moduleMap = new Map<number, Module>();
    const topItems: Array<TopLink> = [];
    for (const rawModule of moduleData) {
        // Loose item (no module, just a top-level item)
        if (!rawModule.module_id || !rawModule.module_name) {
            topItems.push({
                "name": rawModule.module_item_name,
                "icon": rawModule.module_item_icon || "fa-link",
            });
            continue;
        }

        // Set the modules (categories)
        if (!moduleMap.has(rawModule.module_id)) moduleMap.set(rawModule.module_id, {
            "id": rawModule.module_id,
            "name": rawModule.module_name,
            "icon": rawModule.module_icon || "fa-folder",
            "links": [],
        });

        // Add the links to the modules
        moduleMap.get(rawModule.module_id)!.links!.push(rawModule.module_item_name);
    }
    const modules = Array.from(moduleMap.values());

    // Update their profile's last usage date
    await connection.query("UPDATE user_profile SET date_last_usage = CURRENT_TIMESTAMP WHERE user_id = ? AND id = ?;", [userId, profileId]);

    return {
        "activeProfileId": activeProfile.id,
        "profiles": userProfiles,
        "topItems": topItems,
        "modules": modules,
    };
}