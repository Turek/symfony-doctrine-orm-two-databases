FROM php:7.2-fpm

# install default PHP extensions
RUN apt-get update && apt-get install -y default-mysql-client net-tools\
    # Required by memcached extension.
    zlib1g-dev libmemcached-dev \
    # Required by gd extension.
    libfreetype6-dev libpng-dev libjpeg-dev \
    # Required by soap extension.
    libxml2-dev \
    # Required for drush sql-sync and rsync.
    rsync ssh

# ENV COMPOSER_ALLOW_SUPERUSER 1
# ENV PHP_XDEBUG_DEFAULT_ENABLE ${PHP_XDEBUG_DEFAULT_ENABLE:-1}
# ENV PHP_XDEBUG_REMOTE_ENABLE ${PHP_XDEBUG_REMOTE_ENABLE:-1}
# ENV PHP_XDEBUG_REMOTE_HOST $(netstat -rn | grep "^0.0.0.0 " | cut -d " " -f10)
# ENV PHP_XDEBUG_REMOTE_PORT ${PHP_XDEBUG_REMOTE_PORT:-9001}
# ENV PHP_XDEBUG_REMOTE_AUTO_START ${PHP_XDEBUG_REMOTE_AUTO_START:-1}
# ENV PHP_XDEBUG_REMOTE_CONNECT_BACK ${PHP_XDEBUG_REMOTE_CONNECT_BACK:-1}
# ENV PHP_XDEBUG_IDEKEY ${PHP_XDEBUG_IDEKEY:-PHPSTORM}
# ENV PHP_XDEBUG_PROFILER_ENABLE ${PHP_XDEBUG_PROFILER_ENABLE:-0}
# ENV PHP_XDEBUG_PROFILER_OUTPUT_DIR ${PHP_XDEBUG_PROFILER_OUTPUT_DIR:-"/tmp"}
# ENV PHP_INI_DIR ${PHP_INI_DIR:-"/usr/local/etc/php"}
#RUN mkdir -p $PHP_INI_DIR/conf.d

RUN env
RUN docker-php-ext-configure gd \
        --with-gd \
        --with-freetype-dir=/usr/ \
        --with-png-dir=/usr/ \
        --with-jpeg-dir=/usr/;

RUN docker-php-ext-install pdo_mysql gd opcache soap;
RUN pecl install "xdebug-2.6.0";
RUN docker-php-ext-enable xdebug;
RUN pecl install "memcached-3.0.4"
RUN docker-php-ext-enable memcached;
RUN ln -s /var/www/html/vendor/bin/drush /bin/drush

WORKDIR /var/www/html/docroot
EXPOSE 9000
