FROM gitea.okami101.io/okami101/frankenphp:8.5

ENV APP_ENV=prod
ARG USER=www-data
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /app

COPY app app/
COPY bootstrap bootstrap/
COPY config config/
COPY database database/
COPY public public/
COPY resources resources/
COPY storage storage/
COPY artisan composer.json composer.lock ./

RUN \
    composer install --no-dev --optimize-autoloader; \
    php artisan octane:install --server=frankenphp -n; \
    useradd -D ${USER}; \
    setcap CAP_NET_BIND_SERVICE=+eip /usr/local/bin/frankenphp; \
    chown -R ${USER}:${USER} /data/caddy && chown -R ${USER}:${USER} /config/caddy; \
    chown -R ${USER}:${USER} bootstrap/cache && chown -R ${USER}:${USER} storage;

USER ${USER}

CMD ["php", "artisan", "octane:frankenphp", "--host=0.0.0.0", "--port=8000"]
