FROM gitea.okami101.io/okami101/frankenphp:8.5

ARG USER=www-data

RUN \
    useradd ${USER}; \
    setcap CAP_NET_BIND_SERVICE=+eip /usr/local/bin/frankenphp; \
    chown -R ${USER}:${USER} /config/caddy /data/caddy; \
    mkdir -p /app; \
    chown -R ${USER}:${USER} /app

USER ${USER}

ENV APP_ENV=prod

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
    php artisan octane:install --server=frankenphp -n;

CMD ["php", "artisan", "octane:frankenphp", "--host=0.0.0.0", "--port=8000"]
