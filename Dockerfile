# This file is a template, and might need editing before it works on your project.
FROM php:7.0-apache

# Customize any core extensions here
#RUN apt-get update && apt-get install -y \
#        libfreetype6-dev \
#        libjpeg62-turbo-dev \
#        libmcrypt-dev \
#        libpng12-dev \
#    && docker-php-ext-install -j$(nproc) iconv mcrypt \
#    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
#    && docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install pdo_mysql
COPY . /var/www/html/
WORKDIR /var/www/html
COPY config/config.php.example config/config.php
RUN sed -i "s|localhost|srv-captain--database-db|g" config/config.php
RUN sed -i "s|XXX|orce|g" config/config.php


