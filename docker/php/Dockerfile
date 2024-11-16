FROM alpine:latest as build
LABEL stage=build

# Установка необходимых пакетов
RUN apk add --no-cache \
    git \
    tzdata \
    curl \
    php83 \
    php83-ctype \
    php83-curl \
    php83-dom \
    php83-exif \
    php83-fileinfo \
    php83-gd \
    php83-iconv \
    php83-intl \
    php83-json \
    php83-mbstring \
    php83-mysqlnd \
    php83-opcache \
    php83-openssl \
    php83-pcntl \
    php83-pdo \
    php83-pdo_mysql \
    php83-pdo_sqlite \
    php83-phar \
    php83-posix \
    php83-session \
    php83-simplexml \
    php83-sockets \
    php83-tokenizer \
    php83-xml \
    php83-xmlreader \
    php83-xmlwriter \
    php83-zip \
    php83-zlib

# Временная зона Москва
RUN cp /usr/share/zoneinfo/Europe/Moscow /etc/localtime && echo Europe/Moscow > /etc/timezone

# Установка compoer
RUN curl -L https://getcomposer.org/download/latest-stable/composer.phar -o /usr/local/bin/composer && chmod +x /usr/local/bin/composer

# Удаляем ненужные пакеты
RUN apk del --no-cache \
    alpine-baselayout \
    alpine-keys \
    apk-tools \
    dpkg \
    dpkg-dev \
    scanelf \
    tzdata

RUN touch /etc/group

RUN echo "root:x:0:0:root:/:/bin/sh" > /etc/passwd

RUN mkdir -p /var/www

RUN ln -s /usr/bin/php83 /bin/php

ARG UID
ARG GID

RUN addgroup --gid $GID wwwdata; exit 0
RUN adduser -u $UID -G wwwdata -h /var/www --disabled-password --gecos "First Last,RoomNumber,WorkPhone,HomePhone" wwwdata; exit 0

# Чтобы сократить размер образа используем пустой образ
FROM scratch

# И копируем все файлы из ранее собранного образа сюда
COPY --from=build / /

RUN php -v

USER wwwdata

WORKDIR /var/www

EXPOSE 8000
