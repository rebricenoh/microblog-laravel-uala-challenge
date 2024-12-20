Microblogging API con Laravel 9
===============================

Esta es una aplicación de microblogging desarrollada con Laravel 9 siguiendo los principios de Clean Architecture, donde los usuarios pueden publicar tweets, seguir a otros usuarios y ver un timeline de los tweets de los usuarios a los que siguen.

## Estructura del proyecto

```plaintext
.
├── app
│   ├── Domain
│   │   ├── Entities
│   │   │   ├── Tweet.php
│   │   │   └── User.php
│   │   ├── Repositories
│   │   │   ├── TweetRepositoryInterface.php
│   │   │   └── UserRepositoryInterface.php
│   │   └── Services
│   │       ├── TimelineService.php
│   │       └── UserService.php
│   ├── Application
│   │   └── UseCases
│   │       ├── CreateTweetUseCase.php
│   │       ├── FollowUserUseCase.php
│   │       └── GetTimelineUseCase.php
│   └── Infrastructure
│       ├── Controllers
│       │   ├── TweetController.php
│       │   └── UserController.php
│       └── Repositories
│           ├── TweetRepository.php
│           └── UserRepository.php
├── database
│   └── migrations
│       ├── 2024_12_19_000000_create_users_table.php
│       ├── 2024_12_19_000001_create_tweets_table.php
│       └── 2024_12_19_000002_create_follows_table.php
├── tests
│   ├── Unit
│   │   ├── CreateTweetUseCaseTest.php
│   │   └── GetTimelineUseCaseTest.php
│   └── Feature
│       └── TweetControllerTest.php
├── docker-compose.yml
├── Dockerfile
└── README.md
```

## Requisitos

- Docker y Docker Compose
- PHP 8.2+
- Composer

## Configuración y ejecución

* Clonar este repositorio:

    ```bash
    https://github.com/rebricenoh/microblog-laravel-uala-challenge.git
    cd microblog-laravel-uala-challenge
    ```

* Copiar el archivo de configuración:

    ```bash
    cp .env.example .env
    ```

* Agregar las siguientes configuraciones en el archivo .env:

    ```bash
    DB_CONNECTION=pgsql
    DB_HOST=db
    DB_PORT=5432
    DB_DATABASE=microblog_laravel
    DB_USERNAME=postgres
    DB_PASSWORD=secret

    REDIS_HOST=redis
    REDIS_PASSWORD=null
    REDIS_PORT=6379
    ```

* Levantar los contenedores:

    ```bash
    docker-compose up -d
    ```
    Esto descargará las imágenes necesarias y construirá los contenedores para ejecutar la aplicación.

* Ejecutar las migraciones:

    ```bash
    docker-compose exec app php artisan migrate --seed
    ```

La aplicación estará disponible en http://localhost:8000

## Endpoints

#### Publicar un tweet

* `POST /api/tweets`
* Content-Type: application/json

    ```json
    {
    "user_id": 1,
    "content": "Este es un tweet de ejemplo"
    }
    ```

#### Seguir a un usuario

* `POST /api/follow`
* Content-Type: application/json

    ```json
    {
    "follower_id": 1,
    "followed_id": 2
    }
    ```

#### Ver el timeline

`GET /timeline?user_id={user_id}`

## Arquitectura

El proyecto sigue una arquitectura limpia con tres capas principales:

#### Dominio

Contiene la lógica de negocio central:
* Entidades (Tweet, User)
* Interfaces de repositorios
* Servicios de dominio

#### Aplicación

Orquesta los casos de uso:
* CreateTweetUseCase
* FollowUserUseCase
* GetTimelineUseCase

#### Infraestructura

Implementaciones concretas:
* Controladores
* Repositorios
* Adaptadores de base de datos

## Optimizaciones

1. **Caché del Timeline**
   - Uso de Redis para cachear los timelines
   - Invalidación selectiva cuando se publican nuevos tweets
   - TTL de 1 hora para mantener la información fresca

2. **Índices de Base de Datos**
   - Índices compuestos en tweets (`user_id`, `created_at`)
   - Índices en relaciones de follows

## Decisiones Técnicas

1. **Base de Datos**
   - PostgreSQL para producción por su:
     - Soporte para consultas complejas
     - Escalabilidad horizontal
     - Consistencia ACID

2. **Caché**
   - Redis para:
     - Timelines de usuarios
     - Datos frecuentemente accedidos
     - Alta disponibilidad

3. **Escalabilidad**
   - Diseño preparado para sharding de base de datos
   - Caché distribuido con Redis
   - Arquitectura stateless para escalado horizontal

## Tests

```bash
docker-compose exec app php artisan test
```

El proyecto incluye:
- Pruebas unitarias para casos de uso
- Pruebas de integración para endpoints
- Pruebas de repositorios

## Consideraciones de producción

1. **Monitoreo**
   - Implementar logging exhaustivo
   - Monitorear rendimiento de Redis y PostgreSQL
   - Alertas para latencia de endpoints

2. **Seguridad**
   - Implementar autenticación JWT
   - Rate limiting por usuario
   - Validación exhaustiva de entrada

3. **Escalabilidad**
   - Configurar replicación de PostgreSQL
   - Cluster de Redis para alta disponibilidad
   - CDN para assets estáticos

## Apagar los contenedores

Para detener y eliminar los contenedores:

```bash
docker-compose down -v
```
