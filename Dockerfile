FROM php:8.2-apache

# Installer calendar
RUN docker-php-ext-install calendar

# Installer PDO MySQL et Xdebug et Activer Apache mod_rewrite (opcache = mise en cache pour un traitement + rapide)
RUN docker-php-ext-install pdo_mysql opcache \
    && a2enmod rewrite

# Installer Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

EXPOSE 80