# Ecoride/Dockerfile

# Utilise une image PHP de base
FROM php:8.2-fpm

# Installe les dépendances système et les extensions PHP
# La commande '&& \' permet d'enchaîner les commandes sur une seule ligne
RUN apt-get update && \
    apt-get install -y libmysqlclient-dev && \
    docker-php-ext-install pdo pdo_mysql mysqli gd && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*