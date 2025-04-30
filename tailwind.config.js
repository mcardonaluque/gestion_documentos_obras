const colors=require('tailwindcss/colors')
import preset from './vendor/filament/support/tailwind.config.preset'
module.exports = {
  theme: {
      extend: {
          fontSize: {
              'xs': '0.75rem',
              'sm': '0.875rem',
              'base': '1rem',
              'lg': '1.125rem',
              'xl': '1.25rem',
              '2xl': '1.5rem',
              // Agrega más tamaños si es necesario
          },
      },
  },
};
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