import SftpClient from "ssh2-sftp-client";

/**
 * Creates a new SFTP client and connects to the SFTP server.
 * @returns The active SFTP connection.
 */
export async function sftpClient(): Promise<SftpClient> {
    const sftpHost = new SftpClient();
    const config = useRuntimeConfig();

    await sftpHost.connect({
        host: config.sftpHost,
        port: Number(config.sftpPort),
        username: config.sftpUsername,
        password: config.sftpPassword,
    });
    return sftpHost;
}
