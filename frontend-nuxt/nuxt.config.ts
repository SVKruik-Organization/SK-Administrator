// https://nuxt.com/docs/api/configuration/nuxt-config

export default defineNuxtConfig({
    nitro: {
        experimental: {
            websocket: true
        }
    },
    vite: {
        server: {
            allowedHosts: ['.test']
        }
    },
    components: {
        dirs: [
            {
                path: "~/components",
                pathPrefix: false,
                extensions: ["vue"]
            }
        ]
    },
    compatibilityDate: "2024-11-01",
    devtools: { enabled: false },
    ssr: false,
    css: [
        "./assets/main.css",
    ],
    modules: [
        "@nuxt/image",
        "@pinia/nuxt",
        "pinia-plugin-persistedstate/nuxt",
        "nuxt-auth-utils",
        "nuxt-cron",
    ],
    cron: {
        runOnInit: true,
        timeZone: "Europe/Amsterdam",
        jobsDir: "core/tss/jobs",
    },
    piniaPluginPersistedstate: {
        storage: "sessionStorage",
        auto: true,
    },
    pinia: {
        storesDirs: ["./stores/**"],
    },
    runtimeConfig: {
        databaseHost: "",
        databasePort: "",
        databaseNameSkp: "",
        databaseNameSka: "",
        databaseUsername: "",
        databasePassword: "",
        mailHost: "",
        mailUsername: "",
        mailPassword: "",
        mailPath: "",
        meilisearchHost: "",
        meilisearchApiKey: "",
        meilisearchMasterKey: "",
        uplinkHost: "",
        uplinkPort: "",
        uplinkUsername: "",
        uplinkPassword: "",
        uplinkExchange: "",
        uplinkRouter: "",
        sftpHost: "",
        sftpPort: "",
        sftpUsername: "",
        sftpPassword: "",
        public: {
            wsUrl: ""
        }
    },
    app: {
        head: {
            title: "SK Administrator",
            meta: [
                { charset: "utf-8" },
                {
                    name: "viewport",
                    content: "width=device-width, initial-scale=1",
                },
                { name: "theme-color", content: "#000000" },
                { name: "canonical", content: "https://admin.stefankruik.com/" },
                {
                    name: "keywords",
                    content:
                        "SK Administrator",
                },
                { name: "owner", content: "Stefan Kruik" },
                { name: "description", content: "The whole of SK Platform at your fingertips." },
                { property: "og:type", content: "website" },
                { property: "og:site_name", content: "SK Administrator" },
                { property: "og:locale", content: "en_US" },
                { property: "og:locale:alternate", content: "nl_NL" },
                {
                    property: "og:image",
                    content:
                        "https://files.stefankruik.com/Products/1280/Administrator.png",
                },
                { property: "og:image:alt", content: "The SK Administrator logo." },
                { property: "og:image:type", content: "image/png" },
                { property: "og:image:width", content: "1280" },
                { property: "og:image:height", content: "640" },
                { property: "og:title", content: "SK Administrator" },
                { property: "og:description", content: "The whole of SK Platform at your fingertips." },
                {
                    property: "twitter:image",
                    content:
                        "https://files.stefankruik.com/Products/1280/Administrator.png",
                },
                { property: "twitter:title", content: "The whole of SK Platform at your fingertips." },
                {
                    property: "twitter:description",
                    content: "The whole of SK Platform at your fingertips.",
                },
                { property: "twitter:card", content: "summary_large_image" },
            ],
            script: [
                { src: "https://kit.fontawesome.com/ffc90f94bc.js", crossorigin: "anonymous" }
            ],
            link: [
                { rel: "preconnect", href: "https://fonts.googleapis.com" },
                { rel: "preconnect", href: "https://fonts.gstatic.com", crossorigin: "" },
                {
                    rel: "stylesheet",
                    href: "https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap"
                }
            ]
        },
    },
    router: {
        options: {
            scrollBehaviorType: 'smooth'
        }
    },
    routeRules: {
        "/panel": { redirect: "/panel/dashboard" },
    }
});
