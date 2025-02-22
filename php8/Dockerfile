FROM php:8.2-cli

# Installer les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    lsb-release \
    ca-certificates \
    apt-transport-https \
    libicu-dev \  
# Ajout de la dépendance manquante pour intl
    libpng-dev libxml2-dev libzip-dev libonig-dev libxslt-dev unzip libpq-dev nodejs npm wget

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    && mv composer.phar /usr/local/bin/composer

# Installer Symfony
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Configurer et installer les extensions PHP nécessaires
RUN docker-php-ext-configure intl \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql opcache intl zip calendar dom mbstring gd xsl

# Installer APCu
RUN pecl install apcu && docker-php-ext-enable apcu

CMD ["tail", "-f", "/dev/null"]

EXPOSE 9000

# Définir le répertoire de travail
WORKDIR /var/www/html/
