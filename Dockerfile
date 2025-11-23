FROM php:8.2-apache

# Установка зависимостей Yii2
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mysqli zip opcache mbstring intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Установка composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Установка memcached
RUN apt-get update && apt-get install -y \
    libmemcached-dev \
    zlib1g-dev \
    libssl-dev \
    libsasl2-dev \
    && pecl install memcached \
    && echo "extension=memcached.so" > /usr/local/etc/php/conf.d/memcached.ini \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Включить mod_rewrite для ЧПУ
RUN a2enmod rewrite

WORKDIR /app