import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import react from "@vitejs/plugin-react";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/js/flow_builder/app.jsx"],
            // The served document root is the project root, one level above this Laravel app,
            // so the compiled bundle belongs in /build there - not core/public/build, which is
            // gitignored and therefore never reaches a customer's install.
            publicDirectory: "..",
            buildDirectory: "build",
            refresh: true,
        }),
        react(),
    ],
    build: {
        emptyOutDir: true,
        assetsDir: "assets",
    },
});
