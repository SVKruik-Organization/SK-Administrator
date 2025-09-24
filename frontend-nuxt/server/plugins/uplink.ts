import amqp from "amqplib";
import type { Channel, Message, Replies } from "amqplib";
import { UplinkMessage } from "~/assets/customTypes";
import { searchIndices } from "../core/dsi/searchEngine";
import { generateIndex } from "../core/dsi/generate";

/**
 * Uplink is a RabbitMQ consumer that listens for messages from the Uplink network.
 * It is used to receive deployment tasks and execute them on the server.
 * 
 * @see https://github.com/SVKruik-Organization/Uplink
 */
export default defineNitroPlugin(async (_nitroApp) => {
    try {
        const runtimeConfig = useRuntimeConfig();
        const channel: Channel | null = await (await amqp.connect({
            "protocol": "amqp",
            "hostname": runtimeConfig.uplinkHost,
            "port": parseInt(runtimeConfig.uplinkPort as string),
            "username": runtimeConfig.uplinkUsername,
            "password": runtimeConfig.uplinkPassword
        })).createChannel();

        if (!channel) throw new Error("Uplink connection missing.");
        channel.assertExchange(runtimeConfig.uplinkExchange, "direct", { durable: false });
        const queue: Replies.AssertQueue = await channel.assertQueue("", { exclusive: true });
        await channel.bindQueue(queue.queue, runtimeConfig.uplinkExchange, runtimeConfig.uplinkRouter);
        log(`[Uplink / Consumer] Listening on exchange '${runtimeConfig.uplinkExchange}' binded to '${runtimeConfig.uplinkRouter}'.`, "info");

        // Listen
        channel.consume(queue.queue, (message: Message | null) => {
            if (message) {
                const messageContent: UplinkMessage = JSON.parse(message.content.toString());
                switch (messageContent.task) {
                    case "Deploy":
                        // if (process.platform === "linux")
                        break;
                    case "Search":
                        log(`[Uplink / Consumer] Updating search index...`, "info");
                        searchIndices.forEach(async (index) => await generateIndex(index));
                        break;
                    default:
                        log(`[Uplink / Consumer] Unknown task: ${messageContent.task}`, "warning");
                        break;
                }
                channel.ack(message);
            }
        }, { noAck: false });
    } catch (error: any) {
        logError(error);
    }
});