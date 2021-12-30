FROM registry.okami101.io/adr1enbe4udou1n/laravel-realworld

WORKDIR /var/www/html

COPY app app/
COPY bootstrap bootstrap/
COPY config config/
COPY database database/
COPY public public/
COPY resources resources/
COPY storage storage/
COPY vendor vendor/
COPY artisan composer.json composer.lock ./

EXPOSE 8000

CMD ["php", "artisan", "octane:start", "--server=swoole"]
