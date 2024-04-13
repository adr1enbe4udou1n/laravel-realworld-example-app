FROM gitea.okami101.io/okami101/frankenphp:8.3

WORKDIR /app

COPY app app/
COPY bootstrap bootstrap/
COPY config config/
COPY database database/
COPY public public/
COPY resources resources/
COPY storage storage/
COPY vendor vendor/
COPY artisan composer.json composer.lock ./

RUN chown -R www-data:www-data storage bootstrap/cache
