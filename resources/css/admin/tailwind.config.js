import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode:'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',

        './resources/views/components/**/*.blade.php',
        './resources/views/livewire/admin/**/*.blade.php',
        './resources/views/livewire/layouts/admin/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },


    plugins: [
        require("daisyui"),
    ],

    daisyui: {
        themes: ["light", "dark", "aqua", "cupcake"],
        darkTheme: "dark",
        base: true,
        styled: true,
        utils: true,
        prefix: "",
        logs: true,
        themeRoot: ":root",
    },
};
