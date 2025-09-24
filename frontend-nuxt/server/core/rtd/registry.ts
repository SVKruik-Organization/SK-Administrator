import type { Peer } from "crossws";

const peerMap = new Map<number, Peer>();
log("[RTD / Server] Peer registry initialized.", "info");

/**
 * Regster a RTD connection peer by user ID.
 * @param userId The ID of the user to register.
 * @param peer The RTD connection peer to register.
 */
export function registerPeer(userId: number, peer: Peer): void {
    peerMap.set(userId, peer);
}

/**
 * Unregister a RTD connection peer by user ID.
 * @param userId The ID of the user to unregister.
 */
export function unregisterPeer(userId: number): void {
    peerMap.delete(userId);
}

/**
 * Get a RTD connection peer by user ID.
 * @param userId The ID of the user to get the peer for.
 * @returns The peer associated with the given user ID, or undefined if not found.
 */
export function getPeer(userId: number): Peer | undefined {
    return peerMap.get(userId);
}
