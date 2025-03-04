# Usamos una imagen base de PHP con Apache
FROM php:7.4-apache

# Copiamos todos los archivos del proyecto al contenedor
COPY . /var/www/html

# Establecemos el directorio de trabajo
WORKDIR /var/www/html

# Asignamos los permisos correctos para la carpeta html y subcarpetas
RUN chown -R www-data:www-data /var/www/html
