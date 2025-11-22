FROM yiisoftware/yii2-php:8.2-apache

RUN apt-get update && \
    apt-get install -y \
        libmemcached-dev \
        zlib1g-dev \
        libssl-dev \
        libsasl2-dev && \
    pecl install memcached && \
    echo "extension=memcached.so" > /usr/local/etc/php/conf.d/memcached.ini && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*