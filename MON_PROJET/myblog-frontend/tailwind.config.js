/** @type {import('tailwindcss').Config} */
const defaultTheme = require('tailwindcss/defaultTheme')
const colors = require('tailwindcss/colors')

export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  darkMode: 'class',
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', ...defaultTheme.fontFamily.sans],
        serif: ['Merriweather', ...defaultTheme.fontFamily.serif], // Good for article body if needed
      },
      colors: {
        // Redefine primary colors to be professional & sober
        primary: {
            DEFAULT: colors.slate[900],
            ...colors.slate
        },
        // We can keep 'indigo' related names if existing code uses them,
        // but map them to our sober palette or just use 'slate' explicitly.
        // For now, let's ensure we have a good 'slate' base.
        slate: colors.slate, 
      }
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
  ],
}