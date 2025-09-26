import { createTransport } from "nodemailer";
import { resolve } from "path";
import { readFileSync } from "fs";
import mjml2html from "mjml";

export async function sendMail(
    to: string,
    subject: string,
    replacements: Array<{
        key: string;
        value: string;
    }>,
    fileName: string,
): Promise<boolean> {
    try {
        // Email login
        const config = useRuntimeConfig();
        const transport = createTransport({
            host: config.mailHost,
            port: 465,
            secure: true,
            auth: {
                user: config.mailUsername,
                pass: config.mailPassword,
            },
        });

        // Preparing the template
        const templatePath = resolve(process.cwd(), "./server/mail/layouts", `${fileName}.mjml`);
        let templateFile = readFileSync(templatePath, "utf-8");
        replacements.forEach((replacement) => {
            templateFile = templateFile.replace(
                `{{ ${replacement.key} }}`,
                replacement.value,
            );
        });
        if (templateFile.includes("{{")) {
            log("MJML template error: Missing replacements.", "error");
            throw new Error("Something went wrong while sending the email.", {
                cause: { statusCode: 1500 },
            });
        }

        // Parsing MJML to HTML
        const html = mjml2html(templateFile, {
            fonts: {
                "Open Sans":
                    "https://fonts.googleapis.com/css2?family=Open+Sans:wght@300..800",
            },
        });
        if (html.errors.length > 0) {
            log(`MJML parsing errors: ${JSON.stringify(html.errors)}`, "error");
            throw new Error("Something went wrong while sending the email.", {
                cause: { statusCode: 1500 },
            });
        }

        const response: boolean = await new Promise((resolve, reject) => {
            transport.sendMail(
                {
                    from: `"SK Administrator" <${config.mailUsername}>`,
                    to: to,
                    subject: subject,
                    html: html.html,
                    replyTo: "me@stefankruik.nl",
                },
                (error, _info) => {
                    if (error) {
                        log(`Error sending mail: ${error.message}`, "error");
                        reject(false);
                    } else resolve(true);
                },
            );
        });
        if (!response)
            throw new Error("Something went wrong while sending the email.", {
                cause: { statusCode: 1500 },
            });
        return response;
    } catch (error: any) {
        logError(error);
        throw error;
    }
}
