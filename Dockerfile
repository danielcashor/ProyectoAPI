# Usamos una imagen base de PHP con Apache
FROM php:7.4-apache

# Copiamos todos los archivos del proyecto al contenedor
COPY . /var/www/html

# Cambiamos el directorio de trabajo
WORKDIR /var/www/html

# Configuramos Apache para que su DocumentRoot sea la carpeta public/
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Habilitamos el módulo rewrite para que Laravel funcione correctamente
RUN a2enmod rewrite

# Asignamos los permisos correctos para la carpeta html y subcarpetas
RUN chown -R www-data:www-data /var/www/html
