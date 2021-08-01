# ./docker/php/Dockerfile

FROM php:8.0-fpm

RUN pecl install apcu

RUN apt update && \
apt install -y \
libzip-dev \
libicu-dev \
gnupg2

RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add -
RUN echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list
RUN apt update && apt install -y yarn
RUN yarn install

RUN touch /.yarnrc
RUN chmod 777 /.yarnrc

RUN mkdir -p /.cache/yarn && mkdir /.yarn
RUN chmod 777 /.cache/yarn && chmod 777 /.yarn

RUN docker-php-ext-configure intl
RUN docker-php-ext-install zip pdo pdo_mysql sockets intl opcache
RUN docker-php-ext-enable apcu zip pdo pdo_mysql sockets intl opcache

COPY ./opcache.ini /usr/local/etc/php/conf.d/opcache.ini

WORKDIR /usr/src/app

RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

RUN PATH=$PATH:/usr/src/app/vendor/bin:bin
