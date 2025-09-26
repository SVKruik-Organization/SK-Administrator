// This script is used to convert MJML files to HTML using the MJML CLI.
// It is only used for development purposes so that you can preview the email templates.
// The app uses different methods to send emails.

import { execSync } from "child_process";

const filename = process.argv[2];
if (!filename) throw new Error("Please provide a filename");

const output = execSync(`./node_modules/.bin/mjml ./server/mail/layouts/${filename}.mjml -o ./server/mail/out/${filename}.html`);
console.log(output.toString());
