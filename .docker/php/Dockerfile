FROM php:7.2.3-fpm

RUN apt-get update \
    && apt-get install -y vim curl debconf subversion git apt-transport-https apt-utils \
    build-essential locales acl mailutils wget zip unzip \
    gnupg gnupg1 gnupg2

RUN docker-php-ext-install mysqli pdo pdo_mysql opcache

COPY php.ini /etc/php/7.2.3/php.ini
COPY php.ini /usr/local/etc/php
COPY php-fpm-pool.conf /etc/php/7.2.3/pool.d/www.conf
COPY opcache.ini /usr/local/etc/php/conf.d/opcache.ini

RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
   mv composer.phar /usr/local/bin/composer

RUN groupadd dev -g 999
RUN useradd dev -g dev -d /home/dev -m

RUN rm -rf /var/lib/apt/lists/*
RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen && \
    echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen && \
    locale-gen

WORKDIR /home/wwwroot/makapi

EXPOSE 9000
CMD ["php-fpm"]