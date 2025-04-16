# Expense Control

## Versiones Recomendadas

| Nombre     | Versión  |
|------------|----------|
| PHP        | 8.3.x    |
| PostgreSQL | 14.x     |
| Composer   | 2.8.x    |
| Docker     | 28.0.x   |

---

## Setup Config Develop

Se debe copiar y renombrar los siguientes archivos:

| Archivo              | Copia                  |
|----------------------|------------------------|
| `docker-compose.yml` | `docker-compose.dev.yml` |
| `.env.example`       | `.env`                 |
| `vite.config.js`     | `vite.config.dev.js`   |
| `entrypoint.sh`      | `entrypoint.dev.sh`    |

---

### Configuración de `docker-compose.dev.yml`

- Eliminar la red interna.
- Sacar de cada imagen el apartado de `networks`.
- Eliminar el contenedor de `networks`.

```yaml
services:
  app:
    container_name: laravel-app
    build:
      context: ./
      dockerfile: app.dockerfile
    working_dir: /var/www
    volumes:
      - ./:/var/www
    environment:
      - "DB_PORT=5432"
      - "DB_HOST=database"
    ports:
      - "9000:9000"

  web:
    container_name: laravel-web
    build:
      context: ./
      dockerfile: web.dockerfile
    working_dir: /var/www
    volumes:
      - ./:/var/www
    ports:
      - 8080:80
    depends_on:
      - app

  database:
    container_name: laravel-database
    image: postgres:11.2
    volumes:
      - dbdata:/var/lib/pgsql
    environment:
      - "POSTGRES_DB=mydb"
      - "POSTGRES_USER=myuser"
      - "POSTGRES_PASSWORD=expensecontrolpassword"
    ports:
        - "54321:5432"

  selenium:
    container_name: laravel-selenium
    image: selenium/standalone-chrome

  adminer:
    container_name: laravel-adminer
    image: adminer
    restart: always
    ports:
      - 8081:8080

  node:
    image: node:18.19.1
    container_name: laravel_node
    working_dir: /var/www
    volumes:
      - ./:/var/www
    entrypoint: ["/var/www/entrypoint-dev.sh"]
    ports:  
      -  "5173:5173"

volumes:
  dbdata: {}
```

## Configuración de .env 
Conexión a la base de datos:

```env
DB_CONNECTION=pgsql
DB_HOST=[tu host de docker-compose.dev.yml]
DB_PORT=[tu puerto de docker-compose.dev.yml]
DB_DATABASE=[tu database de docker-compose.dev.yml]
DB_USERNAME=[tu user de docker-compose.dev.yml]
DB_PASSWORD=[tu pass de docker-compose.dev.yml]
```

## Configuración de vite.config.dev.js

```javascript
export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
  ],
  server: {
    host: '0.0.0.0',
    port: 5173,
    hmr: {
      host: '[tu ip local]',
      port: 5173
    },
  },
});
```


## Configuración de entrypoint.dev.sh

```bash
#!/bin/sh

# Navega al directorio de trabajo
cd /var/www

# Instala dependencias
npm install

# Inicia Vite
npm run dev
```

## Construir imágenes

```bash
docker compose -f docker-compose.dev.yml up -d --build
```