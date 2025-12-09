# Imagen base de PHP con Apache
FROM php:8.2-apache

# Instalar extensiones necesarias para PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Habilitar mod_rewrite (por si lo ocupamos)
RUN a2enmod rewrite

# Copiar el proyecto al contenedor
# (asume que tu carpeta public/ está en la raíz del repo)
COPY . /var/www/html

# Cambiar el DocumentRoot para que apunte a /public
# (donde está tu index.php)
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/000-default.conf \
    /etc/apache2/apache2.conf

WORKDIR /var/www/html

# Exponer el puerto 80 (Render se encarga del resto)
EXPOSE 80
