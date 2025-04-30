const colors=require('tailwindcss/colors')
import preset from './vendor/filament/support/tailwind.config.preset'
 
export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    theme: {
        extend: {
          colors: {
            'custom-blue': 'rgb(28, 20, 99)', // Define tu color personalizado
          },
        },
      },
    plugins:[require('@tailwindcss/forms'),
            require('@tailwindcss/typography'),
        ],
}