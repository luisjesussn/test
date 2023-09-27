# Usa una imagen base de PHP con Apache
FROM php:apache

# Copia los archivos del proyecto al contenedor
COPY . /var/www/html/

# Instala las extensiones de PHP necesarias para la conexión a la base de datos (mysqli)
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Habilita el módulo de reescritura de Apache
RUN a2enmod rewrite

# Inicia el servidor Apache
CMD ["apache2-foreground"]
