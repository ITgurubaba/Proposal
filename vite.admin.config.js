import { defineConfig } from 'vite';
import laravel,{ refreshPaths } from 'laravel-vite-plugin';
import inject from "@rollup/plugin-inject";
import tailwindcss from 'tailwindcss';
import autoprefixer from 'autoprefixer';

export default defineConfig({
    plugins: [
        inject({
            $: 'jquery',
            jQuery: 'jquery',
            include: ['**/*.js', '**/*.jsx', '**/*.ts', '**/*.tsx'],
            exclude: 'node_modules/**',
        }),
        laravel({
            input: [
                'resources/js/admin/app.js',
                'resources/js/plugins/sweetalert2.js',
                'resources/js/plugins/intl-tel.js',
            ],
            refresh:true,
            buildDirectory: "build/admin",
        }),
    ],
    postcss: {
        plugins: [
            tailwindcss.nesting,
            {
                postcss: tailwindcss({
                    config: './resources/css/admin/admin-tailwind.config.js',
                }),
            },
            autoprefixer(),
        ],
    },

});
