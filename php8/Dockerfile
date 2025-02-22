FROM php:8.0.13-cli

# Installer les dépendances
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        locales apt-utils git libicu-dev g++ libpng-dev libxml2-dev libzip-dev libonig-dev libxslt-dev unzip libpq-dev nodejs npm wget \
        apt-transport-https lsb-release ca-certificates

# Configurer les locales
RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen  \
    && echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen \
    && locale-gen

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    && mv composer.phar /usr/local/bin/composer

# Installer Symfony
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Configurer et installer les extensions PHP
RUN docker-php-ext-configure intl \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql opcache intl zip calendar dom mbstring gd xsl

# Installer APCu
RUN pecl install apcu && docker-php-ext-enable apcu

CMD ["tail", "-f", "/dev/null"]

EXPOSE 9000
# Définir le répertoire de travail
WORKDIR /var/www/html/
