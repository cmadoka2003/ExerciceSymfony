# Dockerfile basique pour Symfony PHP 8.1 + serveur intégré

FROM php:8.2-cli

# Installer les extensions PHP nécessaires (pdo, pdo_pgsql, etc.)
RUN apt-get update && apt-get install -y libpq-dev unzip git \
    && docker-php-ext-install pdo pdo_pgsql

# Installer composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installer le binaire Symfony CLI (nécessaire pour symfony-cmd)
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

WORKDIR /app

COPY . /app

RUN composer install --no-dev --optimize-autoloader

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]