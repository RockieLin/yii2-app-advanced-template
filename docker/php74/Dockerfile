#備份用 實際執行會從dockerbaobao/php74:shopTool pull, 不會build此檔案
FROM phpdockerio/php74-fpm:latest
ARG DEBIAN_FRONTEND=noninteractive

WORKDIR /etc/php/7.4/fpm

### set timezome
ENV TZ=Asia/Taipei
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN groupadd nginx && useradd -g nginx nginx

RUN mkdir -p /var/lib/php/session && mkdir -p /var/lib/php/wsdlcache && mkdir -p /var/log/php
RUN chown nginx:nginx /var/lib/php/session && chown nginx:nginx /var/lib/php/wsdlcache && chown nginx:nginx /var/log/php
RUN chmod 770 /var/lib/php/session && chmod 770 /var/lib/php/wsdlcache && chmod 770 /var/log/php

#composer
RUN  curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

RUN apt-get update
RUN apt-get install -y software-properties-common
RUN LC_ALL=C.UTF-8 add-apt-repository -y ppa:ondrej/php && apt-get update && \
    apt-get install php7.4-mysqli php7.4-mbstring php7.4-common php-redis php7.4-bcmath php7.4-gd php-imagick php7.4-intl php7.4-soap php-pear php7.4-dev -y

RUN apt install php7.4-imagick php7.4-memcached php-apcu php7.4-redis -y && \
    apt-get update && \
    apt-get install -y  git \
    openssl \
    libmcrypt-dev \
    libssl-dev \
    libpng-dev \
    libevent-dev \
    gcc \
    curl \
    git \
    openssl \
    wget \
    autoconf \
    telnet \
    vim \
    zip \
    unzip \
    supervisor && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

RUN pecl install event
RUN echo 'extension=event.so' | tee /etc/php/7.4/mods-available/event.ini
RUN phpenmod -v 7.4 event
RUN apt autoremove

EXPOSE 9000