FROM php:7.3-apache
LABEL maintainer="egdoc.dev@gmail.com"


RUN apt-get update && apt-get -y upgrade

RUN docker-php-ext-install mysqli

RUN apt-get install -y zip

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN a2enmod rewrite

RUN service apache2 restart
