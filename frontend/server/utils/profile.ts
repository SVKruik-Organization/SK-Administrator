import { Pool } from "mariadb/*";
import { Languages, Module, Profile, ProfileData, TopLink, UserTypes } from "@/assets/customTypes";
import { normalizeUrl } from "@/utils/format";

/**
 * Retrieves the profile data for a user.
 * @param objectId The user ID, the owner of the profiles.
 * @param objectType The type of user (User or Guest).
 * @param profileId The specific profile to load, or 0 for the most recently used profile.
 * @param connection An active database connection.
 * @returns The profile data for the user.
 */
export async function getProfileData(objectId: number, objectType: UserTypes, profileId: number, recursion: boolean, connection: Pool): Promise<ProfileData> {
    // Retrieve the user profile
    const rawUserProfiles: Array<Profile> = await connection.query("SELECT id, name, description, position, date_last_usage FROM user_profile WHERE object_id = ? AND object_type = ? ORDER BY date_last_usage DESC, position ASC;", [objectId, objectType]);
    const userProfiles: Array<Profile> = rawUserProfiles.sort((a, b) => a.position - b.position);
    const chosenProfile: Profile | undefined = profileId === 0
        ? rawUserProfiles[0] // Most recently used
        : rawUserProfiles.find(profile => profile.id === profileId); // Specific profile
    if (!chosenProfile) throw new Error(`The selected profile ${profileId} does not exist.`, { cause: { statusCode: 1403 } });

    // Retrieve the modules and module items from the database
    const moduleData: Array<{
        "module_id": number,
        "module_name": { [lang in Languages]: string } | null,
        "module_icon": string | null,
        "module_item_name": { [lang in Languages]: string },
        "module_item_icon": string | null,
        "module_item_id": number | null,
        "language": Languages | null
    }> = await connection.query(`
            SELECT
                IFNULL(module.id, 0) AS module_id,
                module.name AS module_name,
                module.icon AS module_icon,
                module_item.name AS module_item_name,
                module_item.icon AS module_item_icon,
                module_item.id AS module_item_id,
                language
            FROM
                module
                LEFT JOIN user_profile_module ON module_id = module.id
                LEFT JOIN user_profile ON user_profile.id = user_profile_module.user_profile_id
                RIGHT JOIN module_item ON module_item.module_id = module.id
                LEFT JOIN user ON user.id = ?
                LEFT JOIN role ON role.id = user.role_id
                LEFT JOIN \`grant\` ON \`grant\`.role_id = user.role_id
            WHERE
                (user_profile.id = ?
                AND user_profile.object_id = ?
                AND user_profile.object_type = ?
                OR module_item.module_id IS NULL)
                AND \`grant\`.module_item_id = module_item.id
                AND \`grant\`.read = 1;`, [objectId, chosenProfile.id, objectId, objectType]);
    const moduleMap = new Map<number, Module>();
    const topLinks: Array<TopLink> = [];
    let language: Languages = Languages.EN;
    for (const rawModule of moduleData) {
        language = rawModule.language ? rawModule.language : language;

        // Loose item (no module, just a top-level item)
        if (!rawModule.module_id || !rawModule.module_name) {
            topLinks.push({
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
    await connection.query("UPDATE user_profile SET date_last_usage = CURRENT_TIMESTAMP WHERE id = ?;", [chosenProfile.id]);

    // First item URL for default redirects
    let firstItemUrl = "/";
    if (topLinks.length > 0 && topLinks[0]?.name?.en) {
        firstItemUrl = normalizeUrl(`/panel/${topLinks[0].name.en}`);
    } else if (
        modules.length > 0 &&
        modules[0]?.name?.en &&
        modules[0].links?.length &&
        modules[0].links[0]?.en
    ) {
        firstItemUrl = normalizeUrl(`/panel/${modules[0].name.en}/${modules[0].links[0].en}`);
    }

    if (firstItemUrl === "/" && !recursion) {
        // Find another profile with available modules
        const otherProfiles = userProfiles.filter(profile => profile.id !== chosenProfile.id);
        for (const profile of otherProfiles) {
            const profileData = await getProfileData(objectId, objectType, profile.id, true, connection);
            if (profileData.firstItemUrl !== "/") return profileData;
        }

        // No modules found for any profile, throw an error
        throw new Error(`Your last used profile '${chosenProfile.name}' does not have any accessible modules. Contact an Administrator as you do not have any alternative profiles to try.`, { cause: { statusCode: 1403 } });
    }

    return {
        "activeProfileId": chosenProfile.id,
        "firstItemUrl": firstItemUrl,
        "profiles": userProfiles,
        "topLinks": topLinks,
        "modules": modules,
        "language": language
    };
}