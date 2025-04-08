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
        quaternary: '#4a306d', 
        quinary: '#b388eb',    
        senary: '#f0e6ff'
      }
    },
  },
  plugins: [],
}

