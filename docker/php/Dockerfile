FROM php:8.1-alpine

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions @composer opcache pdo_pgsql pcntl sockets swoole@4.8.3
