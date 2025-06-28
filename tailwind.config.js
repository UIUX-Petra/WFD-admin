// tailwind.config.js
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      fontFamily: {
        gotham: ['Gotham', 'sans-serif'], // <- ini barunya
      },
    },
  },
  darkMode: 'class',
  plugins: [],
}
