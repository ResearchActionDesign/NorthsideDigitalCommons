FROM php:5.6-apache
RUN pecl install xdebug-2.5.0
RUN docker-php-ext-install -j$(nproc) mysqli exif && docker-php-ext-enable xdebug
RUN sed -i '1 a xdebug.remote_autostart=true' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
sed -i '1 a xdebug.remote_mode=req' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
sed -i '1 a xdebug.remote_handler=dbgp' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
sed -i '1 a xdebug.remote_connect_back=0 ' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
sed -i '1 a xdebug.remote_port=9000' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
sed -i '1 a xdebug.remote_host=10.254.254.254' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
sed -i '1 a xdebug.remote_enable=1' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
sed -i '1 a xdebug.remote_log=/tmp/xdebug.log' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN a2enmod rewrite
