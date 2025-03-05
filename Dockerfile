# Usamos una imagen base de PHP con Apache
FROM php:7.4-apache

# Instalamos las extensiones necesarias para Laravel
RUN apt-get update && apt-get install -y unzip git libpng-dev && \
    docker-php-ext-install pdo pdo_mysql gd

# Instalamos Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiamos todos los archivos del proyecto al contenedor
COPY . /var/www/html

# Cambiamos el directorio de trabajo
WORKDIR /var/www/html

# Configuramos Apache para que su DocumentRoot sea la carpeta public/
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Habilitamos el módulo rewrite para que Laravel funcione correctamente
RUN a2enmod rewrite

# Instalamos las dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Asignamos los permisos correctos para la carpeta html y subcarpetas
RUN chown -R www-data:www-data /var/www/html && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
