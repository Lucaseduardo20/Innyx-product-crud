/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#7e4997',
        secondary: '#ffffff',
        tertiary: '#e5ff91',
      },
    },
  },
  plugins: [],
}

