/**
 * Generate a random string.
 * @return {string} The random generated ticket.
 */
export function createTicket(): string {
    const characters: string = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    let ticket: string = "";

    for (let i = 0; i < 8; i++) {
        const randomIndex: number = Math.floor(Math.random() * characters.length);
        ticket += characters.charAt(randomIndex);
    }

    return ticket;
}
