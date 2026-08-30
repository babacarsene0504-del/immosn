module.exports = {
  content: ["./View/**/*.php"],
  theme: {
    extend: {
      colors: {
        primary: { DEFAULT: '#C2542E', dark: '#8F3C1F' },
        secondary: { DEFAULT: '#0F6E56', dark: '#085041' },
        sand: { 50: '#FBF6EF', 100: '#F3E9D8', 200: '#E8DFCF' },
      },
      fontFamily: {
        heading: ['Poppins', 'sans-serif'],
        sans: ['Inter', 'sans-serif'],
      },
    },
  },
  plugins: [],
}
