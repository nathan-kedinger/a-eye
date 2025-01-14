/** @type {import('tailwindcss').Config} */
import daisyui from 'daisyui'
export default {
  content: [
      "./index.html",
      './src/**/*.{vue,js,ts}'
  ],
  theme: {
    extend: {},
  },
  plugins: [daisyui],
}
