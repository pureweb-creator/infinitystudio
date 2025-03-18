FROM php:8.3-fpm

WORKDIR /app

RUN apt-get update \
    && apt-get install -y libmemcached-dev zlib1g-dev libpng-dev libssl-dev curl git \
    && yes '' | pecl install memcached \
    && docker-php-ext-enable memcached \
    && docker-php-ext-install mysqli pdo pdo_mysql sockets

COPY --from=composer /usr/bin/composer /usr/bin/composer

ARG UNAME=docker
ARG GID=1000
ARG UID=1000
RUN groupadd -g $GID dockergroup && \
    useradd -m -u $UID -g $GID -s /bin/bash $UNAME

USER $UNAME