FROM php:7.4.32-fpm

# ставим необходимые для нормальной работы модули
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
	libpng-dev \
	libonig-dev \
	libzip-dev \
	libmemcached-dev \
	libmcrypt-dev \
        libpq-dev \
        && docker-php-ext-install -j$(nproc) iconv mbstring mysqli pdo_mysql zip pdo_pgsql pgsql \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
        && docker-php-ext-install -j$(nproc) gd \
        && pecl install redis \
     && pecl install memcached && \
        docker-php-ext-enable memcached redis

#RUN rm /usr/local/etc/php-fpm.d/zz-docker.conf
COPY zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf
COPY php.ini /usr/local/etc/php/conf.d/php-custom.ini

ARG IS_ADD_XDEBUG
RUN if [ "$IS_ADD_XDEBUG" = "true" ]; then \
    # Установка Xdebug
    pecl install xdebug && \
    docker-php-ext-enable xdebug && \
\
    # Конфигурирование Xdebug
    echo "zend_extension=xdebug.so" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.idekey=\"PHPSTORM\"" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.mode=develop,debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.client_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.discover_client_host=0" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
fi


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
#RUN composer install

CMD ["php-fpm"]
