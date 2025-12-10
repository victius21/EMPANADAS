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

# 🔹 Cambiar DocumentRoot de Apache a /var/www/html/public
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/000-default.conf \
    /etc/apache2/apache2.conf

# Carpeta de trabajo
WORKDIR /var/www/html

# Copiar TODO el proyecto al contenedor
COPY . /var/www/html

# (Opcional) Asegurar permisos
RUN chown -R www-data:www-data /var/www/html
