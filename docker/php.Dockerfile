FROM php:8.2-cli-alpine3.17

ARG COMPOSER_BIN=/usr/bin/composer
ARG COMPOSER_VERSION=2.5.5
ARG COMPOSER_SHA256SUM=566a6d1cf4be1cc3ac882d2a2a13817ffae54e60f5aa7c9137434810a5809ffc

RUN apk add --no-cache tini make

RUN apk add --no-cache --virtual BuildDeps autoconf g++ linux-headers \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && rm -rf /tmp/pear; apk del BuildDeps;

RUN set -ex ;\
  wget -O "$COMPOSER_BIN" "https://getcomposer.org/download/$COMPOSER_VERSION/composer.phar" ;\
  printf "%s  %s\n" "$COMPOSER_SHA256SUM" "$COMPOSER_BIN" | sha256sum -c - ;\
  chmod +x -- "$COMPOSER_BIN" ;\
  composer --version ;\
  composer diagnose || printf 'composer diagnose exited: %d\n' $? ;\
  :

COPY ./docker/docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/

ENTRYPOINT ["/sbin/tini", "--"]
WORKDIR /app
