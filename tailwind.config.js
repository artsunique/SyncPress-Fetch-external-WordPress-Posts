/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './**/*.php', // Überwacht alle PHP-Dateien im Plugin-Verzeichnis
    './src/**/*.js', // Optional: wenn Sie JavaScript-Dateien haben, die Tailwind-Klassen verwenden
    // Weitere Pfade hinzufügen, falls erforderlich
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

