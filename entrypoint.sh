#!/bin/bash
set -e

# Exécute les migrations Doctrine (ne bloque pas si rien à faire)
php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration

# Lance le serveur PHP intégré
php -S 0.0.0.0:8000 -t public