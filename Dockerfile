FROM php:5.6-apache
RUN docker-php-ext-install -j$(nproc) mysqli exif
RUN a2enmod rewrite
