FROM node:20-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json ./

RUN npm ci --ignore-scripts

COPY postcss.config.js tailwind.config.js vite.config.js ./
COPY resources/ resources/

RUN npm run build

FROM composer:2.7 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --no-suggest \
    --optimize-autoloader \
    --ignore-platform-req=ext-*

FROM php:8.2-fpm-alpine AS app

RUN apk add --no-cache \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_pgsql \
    pgsql \
    gd \
    zip \
    bcmath \
    opcache

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

COPY --from=vendor /app/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build

RUN mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

RUN php artisan optimize:clear \
    && php artisan optimize \
    && php artisan view:cache \
    && php artisan route:cache \
    && php artisan config:cache

EXPOSE 9000

CMD ["php-fpm"]

FROM nginx:1.27-alpine AS nginx

RUN apk add --no-cache curl

WORKDIR /var/www/html

COPY --from=app /var/www/html /var/www/html

COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD curl -f http://localhost || exit 1

CMD ["nginx", "-g", "daemon off;"]
