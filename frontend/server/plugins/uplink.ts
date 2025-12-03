import { searchIndices } from "../core/dsi/searchEngine";
import { generateIndex } from "../core/dsi/generate";
import { mountUplink, UplinkMessage } from "@svkruik/sk-uplink-connector";
import { logData, logError } from "@svkruik/sk-platform-formatters";

/**
 * Uplink is a RabbitMQ consumer that listens for messages from the Uplink network.
 * It is used to receive deployment tasks and execute them on the server.
 * 
 * @see https://github.com/SVKruik-Organization/Uplink
 */
export default defineNitroPlugin(async (_nitroApp) => {
    try {
        const runtimeConfig = useRuntimeConfig();
        await mountUplink(taskHandler, undefined, {
            "host": runtimeConfig.uplinkHost,
            "port": runtimeConfig.uplinkPort,
            "username": runtimeConfig.uplinkUsername,
            "password": runtimeConfig.uplinkPassword,
            "exchangeName": runtimeConfig.uplinkExchange,
            "routingKey": runtimeConfig.uplinkRouter
        });
    } catch (error: any) {
        logError(error);
    }
});

function taskHandler(message: UplinkMessage) {
    switch (message.task) {
        case "Search":
            logData(`[Uplink / Consumer] Updating search index...`, "info");
            searchIndices.forEach(async (index) => await generateIndex(index));
            break;
    }
}