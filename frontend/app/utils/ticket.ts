/**
 * Generate a random string.
 * Used for reference support tickets, images, etc.
 * 
 * @param length The maximum length of the string.
 * @return The random generated ticket.
 */
export function createTicket(length: number = 16): string {
    const characters: string = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    let ticket: string = "";

    for (let i = 0; i < length; i++) {
        const randomIndex: number = Math.floor(Math.random() * characters.length);
        ticket += characters.charAt(randomIndex);
    }

    return ticket;
}
