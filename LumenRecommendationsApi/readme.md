# Lumen Recommendations API

Microservicio de recomendaciones para la arquitectura de microservicios de libros.

## Descripcion

Este servicio proporciona recomendaciones de libros basadas en:
- Popularidad (libros mas vistos/interactuados)
- Similitud (libros del mismo autor)
- Autor (todos los libros de un autor especifico)

## Puerto

- **Puerto**: 8014

## Endpoints

### Recomendaciones

- `GET /recommendations/popular` - Obtiene libros populares basados en interacciones
- `GET /recommendations/book/{book_id}/similar` - Obtiene libros similares (mismo autor)
- `GET /recommendations/author/{author_id}` - Obtiene libros de un autor especifico
- `GET /recommendations/stats` - Estadisticas de interacciones

### Interacciones

- `GET /interactions` - Lista todas las interacciones
- `POST /interactions` - Registra una nueva interaccion

## Instalacion

```bash
cd LumenRecommendationsApi
composer install
cp .env.example .env
# Editar .env con la ruta correcta de la base de datos
touch database/database.sqlite
php artisan migrate
```

## Ejecucion

```bash
php -S localhost:8014 -t public
```

## Dependencias

Este servicio consume:
- **Books Service** (puerto 8002)
- **Authors Service** (puerto 8001)

## Ejemplo de Uso

```bash
# Registrar una interaccion
curl -X POST http://localhost:8014/interactions \
  -H "Content-Type: application/json" \
  -d '{"book_id": 1, "interaction_type": "view"}'

# Obtener libros populares
curl http://localhost:8014/recommendations/popular

# Obtener libros similares
curl http://localhost:8014/recommendations/book/1/similar

# Obtener libros de un autor
curl http://localhost:8014/recommendations/author/1
```
