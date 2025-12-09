# Imagen base: PHP 8.2 con Apache
FROM php:8.2-apache

# Actualizar paquetes e instalar extensiones PDO para MySQL y PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql \
    && a2enmod rewrite

# Copiar todo el proyecto al contenedor
COPY . /var/www/html

# Cambiar el DocumentRoot para que apunte a /public (donde está tu index.php)
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/000-default.conf \
    /etc/apache2/apache2.conf

# Directorio de trabajo
WORKDIR /var/www/html

# Puerto que expone Apache (Render se encarga del mapeo externo)
EXPOSE 80
