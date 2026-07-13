import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Outfit', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: {
                'premium': '0 20px 40px -15px rgba(0, 0, 0, 0.05)',
                'glow': '0 0 25px rgba(79, 70, 229, 0.15)',
            }
        },
    },

    plugins: [forms],
};
