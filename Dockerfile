# Usamos una imagen base de PHP con Apache
FROM php:8.0-apache

# Instalamos las extensiones necesarias para Laravel
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libpng-dev \
    mariadb-client \
    && docker-php-ext-install pdo pdo_mysql gd \
    && rm -rf /var/lib/apt/lists/*

# Aumentamos el límite de memoria de PHP
RUN echo "memory_limit=2G" > /usr/local/etc/php/conf.d/memory-limit.ini

# Instalamos Composer de forma manual para evitar errores
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiamos los archivos del proyecto al contenedor
COPY . /var/www/html

# Cambiamos al directorio de trabajo
WORKDIR /var/www/html

# Configuramos Apache para que su DocumentRoot sea la carpeta public/
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Habilitamos el módulo rewrite para que Laravel funcione correctamente
RUN a2enmod rewrite

# Instalamos las dependencias de Laravel
RUN composer install --optimize-autoloader --prefer-dist --no-interaction --no-progress

# Asignamos los permisos correctos para Laravel
RUN chown -R www-data:www-data /var/www/html && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Exponemos el puerto 80
EXPOSE 80

# Comando de inicio del contenedor
CMD ["apache2-foreground"]
