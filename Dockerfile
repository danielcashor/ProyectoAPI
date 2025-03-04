# Usa una imagen oficial de PHP
FROM php:8.0-apache

# Copia tu código a la carpeta del contenedor
COPY . /var/www/html/

# Habilita el módulo de reescritura de Apache
RUN a2enmod rewrite

# Establece el puerto del contenedor
EXPOSE 80

# Inicia Apache cuando el contenedor se ejecute
CMD ["apache2-foreground"]
