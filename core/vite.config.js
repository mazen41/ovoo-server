import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import react from "@vitejs/plugin-react";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/js/flow_builder/app.jsx"],
            refresh: true,
        }),
        react(),
    ],
    build: {
        outDir: "public/build",
        emptyOutDir: true,
        assetsDir: "assets"
    },
});
