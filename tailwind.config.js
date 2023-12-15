const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        'Modules/*/resources/js/**/*.vue',
    ],
    darkMode: 'false',
    safelist: [
        {
            pattern: /grid-cols-+/,
        }
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter var', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'tf-blue': {
                    50: '#a7d8ff',
                    100: '#93cfff',
                    200: '#80c5ff',
                    300: '#5cb5ff',
                    400: '#2fa9ff',
                    500: '#0094ff',
                    600: '#007ad3',
                    700: '#006dbb',
                    800: '#005fa4',
                    900: '#00528c',
                    950: '#00497c',
                }
            },
            minHeight: (theme) => ({
                ...theme('spacing'),
            }),
            minWidth: (theme) => ({
                ...theme('spacing'),
            }),
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio'),
        require('@vueform/slider/tailwind')
    ],
};
