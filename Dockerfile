FROM php:8.1-cli

WORKDIR /app

# Instalar extensões e dependências do sistema
RUN apt-get update && apt-get install -y unzip libzip-dev     && docker-php-ext-install pdo_mysql

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Copiar código
COPY . .

# Instalar dependências PHP
RUN composer install --no-dev --optimize-autoloader

EXPOSE 8081
