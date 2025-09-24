import { MeiliSearch } from "meilisearch";

// Don't forget to update the lookups in ./generate.ts when you alter this list
export const searchIndices: Array<string> = [];

/**
 * Create a connection to the MeiliSearch instance.
 * @returns The MeiliSearch instance.
 */
export async function searchEngine(): Promise<MeiliSearch> {
    const config = useRuntimeConfig();
    return new MeiliSearch({
        host: config.meilisearchHost,
        apiKey: config.meilisearchApiKey,
    });
}

/**
 * Create a connection to the MeiliSearch instance with the master key.
 * Used for index CRUD operations.
 * DO NOT USE FOR CONVENTIONAL SEARCHING.
 * @returns The MeiliSearch instance.
 */
export async function masterEngine(): Promise<MeiliSearch> {
    const config = useRuntimeConfig();
    return new MeiliSearch({
        host: config.meilisearchHost,
        apiKey: config.meilisearchMasterKey,
    });
}

/**
 * Check if the index is valid.
 * @param index The index to check.
 * @returns If the index is valid.
 */
export function isValidIndex(index: string | Array<string>): boolean {
    if (Array.isArray(index)) {
        return index.every((i) => searchIndices.includes(i));
    } else return searchIndices.includes(index);
}