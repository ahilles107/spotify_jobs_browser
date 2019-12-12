FROM php:7.4.0-fpm-alpine3.10

RUN apk update

RUN docker-php-ext-install ctype iconv json

RUN curl --insecure https://getcomposer.org/composer.phar -o /usr/bin/composer && chmod +x /usr/bin/composer

# Set timezone
RUN ln -s /usr/share/zoneinfo/Europe/Warsaw /etc/localtime
RUN "date"

WORKDIR /var/www/symfony
