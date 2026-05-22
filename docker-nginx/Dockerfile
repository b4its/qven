FROM php:8.4-fpm

# 1. Install core system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    git \
    curl \
    mariadb-client

# 2. Install Node.js & NPM (Dibuat terpisah agar aman dari break-line)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# 3. Install PHP extensions
RUN docker-php-ext-install pdo_mysql gd zip intl

# 4. Git 
RUN git config --global --add safe.directory /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html