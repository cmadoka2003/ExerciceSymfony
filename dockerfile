# Étape 1 : Build des assets avec Node.js
FROM node:18 as node_builder

WORKDIR /app

# Copier les fichiers package.json et package-lock.json/yarn.lock
COPY package*.json ./

# Installer les dépendances JS
RUN npm install

# Copier le reste de l'app
COPY . .

# Compiler les assets en prod
RUN npm run build


# Étape 2 : Image PHP + Symfony + Composer
FROM php:8.2-cli

# Installer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y libpq-dev unzip git \
    && docker-php-ext-install pdo pdo_pgsql

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Créer un utilisateur symfony
RUN useradd -m symfony

WORKDIR /app

# Copier tout le code, sauf les assets compilés (on va les copier depuis node_builder)
COPY . .

# Copier les assets compilés depuis node_builder
COPY --from=node_builder /app/public/build /app/public/build

# Changer les droits pour l'utilisateur symfony
RUN chown -R symfony:symfony /app

USER symfony

# Installer les dépendances PHP sans les dev
RUN composer install --no-dev --optimize-autoloader \
    && php bin/console cache:clear \
    && php bin/console assets:install public

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]