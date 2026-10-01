FROM php:8.2-apache

# curl isn't built into the base image; opcache is a free production speedup.
RUN apt-get update && apt-get install -y --no-install-recommends \
        ca-certificates \
        libcurl4-openssl-dev \
    && docker-php-ext-install opcache curl \
    && apt-get purge -y --auto-remove libcurl4-openssl-dev \
    && a2enmod rewrite headers deflate expires \
    && sed -ri -e 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY . /var/www/html

RUN php scripts/setup-ytdlp.php \
    && mkdir -p /var/www/html/cache/rate-limit \
    && chown -R www-data:www-data /var/www/html/cache /var/www/html/bin \
    && chmod -R 775 /var/www/html/cache

EXPOSE 80
