# Utilitza la imatge oficial de PHP amb Apache
FROM php:8.2-apache

# Instal·la les extensions necessàries de PHP
RUN docker-php-ext-install pdo pdo_mysql

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Habilita el mòdul de reescriptura d'Apache
RUN a2enmod rewrite

# Configura el DocumentRoot de Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copia els fitxers de l'aplicació al directori de treball del contenedor
COPY . /var/www/html

# Assigna permisos adequats als fitxers i directoris
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Exposa el port 80
EXPOSE 80
