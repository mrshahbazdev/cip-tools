/** @type {import('tailwindcss').Config} */
module.exports = {
  // Content: Woh files jahan Tailwind classes use ho rahi hain aur compile ki jaani hain.
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    // Filament aur Livewire ke components ko shamil karein
    "./vendor/filament/**/*.blade.php",
    "./app/Http/Livewire/**/*.php",
  ],
  theme: {
    // Yahan aap custom theme settings kar sakte hain
    extend: {
        fontFamily: {
            // Default font Inter set kiya gaya hai
            sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
        },
    },
  },
  plugins: [],
}
