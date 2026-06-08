#!/bin/sh

# Navega al directorio de trabajo
cd /var/www

# Instala las dependencias
npm install

# Evita que Laravel intente cargar assets desde el servidor dev de Vite.
rm -f public/hot

# Inicia Vite
npm run build
