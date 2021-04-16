FROM php:7.3-apache
LABEL maintainer="egdoc.dev@gmail.com"

# Update the image to the latest packages
RUN apt-get update && apt-get -y upgrade

# Copy all the composer binaries from the system so that we can use them for our application
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Install mysqli
RUN docker-php-ext-install mysqli

# Install zip
RUN apt-get install -y zip

# Enable rewrite engine (this will allow apache to rewrite URI's to the base URL)
RUN a2enmod rewrite