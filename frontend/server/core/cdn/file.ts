import { FileTypes } from "@/assets/customTypes";
import { sftpClient } from "./sftp";
import sharp from "sharp";
import ffmpegPath from "ffmpeg-static";
import ffmpeg from "fluent-ffmpeg";
ffmpeg.setFfmpegPath(ffmpegPath as string);
import path from "path";
import fs from "fs/promises";

/**
 * Upload a file to the SFTP server.
 * @param name The name of the file to upload. This is the Ticket name, so without the file extension.
 * @param type The type of the file to upload.
 * @param file The file itself.
 * @returns Status of the operation.
 */
export async function uploadFile(name: string, type: FileTypes, file: File): Promise<boolean> {
    const sftp = await sftpClient();
    let remotePath: string = `/files/${type}/${name}`;

    sftp.end();
    return true;
}

/**
 * Check if a file exists on the SFTP server.
 * @param fullName The name of the file to check. This includes the file extension.
 * @param type The type of the file to check.
 * @returns Whether the file exists or not.
 */
export async function checkFile(fullName: string, type: FileTypes): Promise<boolean> {
    const sftp = await sftpClient();
    const remotePath: string = `/files/${type}/${fullName}`;
    const exists = await sftp.exists(remotePath);

    sftp.end();
    return Boolean(exists);
}

/**
 * Deletes a file from the SFTP server.
 * @param fullName The name of the file to delete. This includes the file extension.
 * @param type The type of the file to delete.
 * @returns Status of the operation.
 */
export async function deleteFile(fullName: string, type: FileTypes): Promise<boolean> {
    const sftp = await sftpClient();
    const remotePath: string = `/files/${type}/${fullName}`;
    const exists = await sftp.exists(remotePath);
    if (!exists) {
        return false;
    } else await sftp.delete(remotePath);

    sftp.end();
    return true;
}

/**
 * Compresses the file based on its type.
 * @param file The file to compress. Can be a video or image, but without the file extension.
 * @param type The type of the file. Different strategies are used for different file types.
 * @returns The compressed file as a Buffer.
 */
async function compressionHandler(name: string, file: File, type: FileTypes): Promise<Buffer> {
    const arrayBuffer: ArrayBuffer = await file.arrayBuffer();

    switch (type) {
        case FileTypes.userAvatar:
        default:
            throw new Error("Unsupported file type");
    }
}

/**
 * Temporarily upload a file locally.
 * @param name The name of the file to upload.
 * @param file The file itself.
 * @returns The local path of the uploaded file.
 */
async function uploadLocally(name: string, file: File): Promise<string> {
    // Local upload path
    const arrayBuffer: ArrayBuffer = await file.arrayBuffer();
    const buffer: Buffer = Buffer.from(arrayBuffer);
    const fileName: string = name.replace(".", "-in.");
    const localPath: string = path.join(process.cwd(), "uploads", fileName);
    await fs.mkdir(path.join(process.cwd(), "uploads"), { recursive: true });
    await fs.writeFile(localPath, buffer);
    return localPath;
}