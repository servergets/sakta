/** @type {import('tailwindcss').Config} */
const preset = require('./vendor/filament/filament/tailwind.config.preset')
export default {
    presets: [preset], 
    content: [
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./resources/**/*.vue",
      "./app/Filament/**/*.php",
      "./resources/views/filament/**/*.blade.php",
      "./vendor/filament/**/*.blade.php",
    ],
    theme: {
      extend: {
        colors: {
          'sakta-primary': '#14b8a6',
          'sakta-secondary': '#0d9488',
        }
      },
    },
    plugins: [
      require('@tailwindcss/forms'),
      require('@tailwindcss/typography'),
    ],
  }