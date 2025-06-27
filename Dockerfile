# Dockerfile para Laravel com PHP 8.2
FROM php:8.2-fpm

# Instalar dependências do sistema e extensões PHP necessárias
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    zip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip

# Instalar composer globalmente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Diretório de trabalho dentro do container
WORKDIR /var/www/html

# Copiar arquivos do projeto para o container
COPY . .

# Instalar dependências PHP do Laravel
RUN composer install --no-dev --optimize-autoloader

# Ajustar permissões para storage e cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Expor porta para PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
