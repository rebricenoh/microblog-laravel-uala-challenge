FROM php:8.2-fpm

# Instalación dependencias del sistema
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instalación extensiones PHP
RUN docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# Instalación de la extensión Redis
RUN pecl install redis \
    && docker-php-ext-enable redis

# Instalación Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar composer.json y composer.lock primero para aprovechar la caché de Docker
COPY composer.json composer.lock ./

# Instalar dependencias
RUN composer install --prefer-dist --no-dev --no-scripts --classmap-authoritative \
    && composer clear-cache

# Copiar el resto de la aplicación
COPY . .

# Generar el autoloader optimizado
RUN composer dump-autoload --optimize

# Establecer permisos adecuados
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000
