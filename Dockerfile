FROM node:20-alpine AS frontend-builder
WORKDIR /app

RUN apk add --no-cache git

COPY package.json package-lock.json* ./
RUN npm install

COPY vite.config.js ./
COPY resources/ ./resources/
COPY public/ ./public/
RUN npm run build

FROM php:8.2-fpm-alpine AS app

WORKDIR /var/www

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
    curl \
    git \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    zip \
    unzip \
    oniguruma-dev \
    icu-dev \
    nodejs \
    npm \
    bash \
    procps \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        zip \
        exif \
        pcntl \
        bcmath \
        gd \
        intl \
        opcache

# Get latest Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer


COPY . .

COPY --from=frontend-builder /app/public/build ./public/build

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN npm install --omit=dev --no-audit

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
