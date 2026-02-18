/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            backdropBlur: {
                '3xl': '48px',
            },
            backgroundOpacity: {
                '5': '0.05',
                '15': '0.15',
            },
            borderOpacity: {
                '15': '0.15',
            }
        },
    },
    plugins: [],
}