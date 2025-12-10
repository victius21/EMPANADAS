# Imagen base de PHP con Apache
FROM php:8.2-apache

# Actualizar e instalar dependencias necesarias
# - libpq-dev     → extensiones de PostgreSQL
# - libssl-dev / pkg-config → para compilar driver de MongoDB
RUN apt-get update && apt-get install -y \
        libpq-dev \
        libssl-dev \
        pkg-config \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && a2enmod rewrite \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Carpeta de trabajo dentro del contenedor
WORKDIR /var/www/html

# Copiar TODO el proyecto al contenedor
COPY . /var/www/html
