FROM gitea.okami101.io/okami101/frankenphp:8.3

ARG USER=www-data

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

RUN \
    useradd -D ${USER}; \
    setcap CAP_NET_BIND_SERVICE=+eip /usr/local/bin/frankenphp; \
    chown -R ${USER}:${USER} /data/caddy && chown -R ${USER}:${USER} /config/caddy; \
    chown -R ${USER}:${USER} bootstrap/cache && chown -R ${USER}:${USER} storage;

USER ${USER}
