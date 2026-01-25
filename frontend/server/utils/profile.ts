import { Languages, Module, Profile, ProfileData, TopLink, UserTypes } from "@/assets/customTypes";
import { normalizeUrl } from "@/utils/format";
import { Pool } from "@svkruik/sk-platform-db-conn";

/**
 * Retrieves the profile data for a user.
 * @param objectId The user ID, the owner of the profiles.
 * @param objectType The type of user (User or Guest).
 * @param profileId The specific profile to load, or null for the most recently used profile.
 * @param connection An active database connection.
 * @returns The profile data for the user.
 */
export async function getProfileData(objectId: string, objectType: UserTypes, profileId: string | null, recursion: boolean, connection: Pool): Promise<ProfileData> {
    // Retrieve the user profile
    const rawUserProfiles: Array<Profile> = await connection.query("SELECT id, name, description, position, last_usage_at FROM user_profiles WHERE object_id = ? AND object_type = ? ORDER BY last_usage_at DESC, position ASC;", [objectId, objectType]);
    const userProfiles: Array<Profile> = rawUserProfiles.sort((a, b) => a.position - b.position);
    const chosenProfile: Profile | undefined = profileId ? rawUserProfiles.find(profile => profile.id === profileId) : rawUserProfiles[0]; // Specific profile or most recently used
    if (!chosenProfile) throw new Error(`The selected profile ${profileId} does not exist.`, { cause: { statusCode: 1403 } });

    // Retrieve the modules and module items from the database
    const moduleData: Array<{
        "module_id": string,
        "module_name": { [lang in Languages]: string } | null,
        "module_icon": string | null,
        "module_item_name": { [lang in Languages]: string },
        "module_item_icon": string | null,
        "module_item_id": string | null,
        "language": Languages | null
    }> = await connection.query(`
            SELECT
                IFNULL(modules.id, 0) AS module_id,
                modules.name AS module_name,
                modules.icon AS module_icon,
                module_items.name AS module_item_name,
                module_items.icon AS module_item_icon,
                module_items.id AS module_item_id,
                language
            FROM
                modules
                LEFT JOIN user_profile_modules ON module_id = modules.id
                LEFT JOIN user_profiles ON user_profiles.id = user_profile_modules.user_profile_id
                RIGHT JOIN module_items ON module_items.module_id = modules.id
                LEFT JOIN users ON users.id = ?
                LEFT JOIN user_roles ON user_roles.id = users.role_id
                LEFT JOIN \`module_item_grants\` ON \`module_item_grants\`.role_id = users.role_id
            WHERE
                (user_profiles.id = ?
                AND user_profiles.object_id = ?
                AND user_profiles.object_type = ?
                OR module_items.module_id IS NULL)
                `, [objectId, chosenProfile.id, objectId, objectType]);

    // AND \`module_item_grants\`.module_item_id = module_items.id
    //AND \`module_item_grants\`.can_read = 1;
    const moduleMap = new Map<string, Module>();
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
    await connection.query("UPDATE user_profiles SET last_usage_at = CURRENT_TIMESTAMP WHERE id = ?;", [chosenProfile.id]);

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