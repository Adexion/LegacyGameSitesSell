# ./docker/php/Dockerfile

FROM php:7.4-fpm

RUN pecl install apcu

RUN apt update && \
apt install -y \
libzip-dev \
nodejs \
npm 

RUN npm install -g yarn

RUN docker-php-ext-enable apcu
RUN docker-php-ext-install zip pdo pdo_mysql sockets

WORKDIR /usr/src/app

RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

RUN PATH=$PATH:/usr/src/app/vendor/bin:bin
