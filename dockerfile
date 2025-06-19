FROM php:8.2-cli

# Installer les extensions PHP nécessaires (pdo, pdo_pgsql, etc.)
RUN apt-get update && apt-get install -y libpq-dev unzip git \
    && docker-php-ext-install pdo pdo_pgsql

# Installer composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Créer le dossier de l'app en avance
WORKDIR /app
COPY . /app

# Crée un utilisateur symfony (non-root)
RUN useradd -m symfony \
    && chown -R symfony:symfony /app

# Change d'utilisateur
USER symfony

# Installer les dépendances (scripts activés)
RUN composer install --no-dev --optimize-autoloader \
    && php bin/console cache:clear \
    && php bin/console assets:install public

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
EXPOSE 8000

CMD ["/entrypoint.sh"]