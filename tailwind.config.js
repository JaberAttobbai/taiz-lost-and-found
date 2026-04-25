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
                sans: ['Cairo', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: '#0F7A5C', // أخضر أساسي
                    light: '#18A07A',   // أخضر فاتح
                    dark: '#0B5C45',    // أخضر داكن
                },
                brand: {
                    text: '#2F2F2F',    // رمادي نصوص
                    bg: '#F9FAFB',      // خلفية فاتحة
                }
            }
        },
    },

    plugins: [forms],
};
