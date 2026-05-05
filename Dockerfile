FROM php:8.3-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libxml2-dev \
    libonig-dev \
    mariadb-client \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions required by Drupal
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        gd \
        pdo \
        pdo_mysql \
        mysqli \
        opcache \
        zip \
        xml \
        mbstring \
        bcmath

# Usar configuración PHP de producción (zend.assertions=-1 evita AssertionError en Drush)
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

# Configure Apache
RUN a2enmod rewrite
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Create Drupal project via Composer
WORKDIR /var/www/html
RUN COMPOSER_MEMORY_LIMIT=-1 composer create-project drupal/recommended-project:^11 . --no-interaction

# Install Drush
RUN COMPOSER_MEMORY_LIMIT=-1 composer require drush/drush --no-interaction

# System-level Drush config — sets root+uri BEFORE CLI options are parsed,
# fixing EmptyBoot TypeError in BootstrapManager::refineUriSelection().
RUN mkdir -p /etc/drush \
    && echo "options:" > /etc/drush/drush.yml \
    && echo "  root: /var/www/html/web" >> /etc/drush/drush.yml \
    && echo "  uri: http://localhost" >> /etc/drush/drush.yml

# Install contrib modules (D11 compatible)
RUN COMPOSER_MEMORY_LIMIT=-1 composer require \
    drupal/admin_toolbar \
    drupal/pathauto \
    drupal/token \
    drupal/ctools \
    drupal/redirect \
    drupal/metatag \
    drupal/gin \
    --no-interaction

RUN COMPOSER_MEMORY_LIMIT=-1 composer require \
    drupal/views_bulk_operations \
    --no-interaction

# Copy project files (settings, theme, setup scripts, entrypoint)
COPY web/sites/default/settings.php        /var/www/html/web/sites/default/settings.php
COPY web/themes/custom/                    /var/www/html/web/themes/custom/
COPY setup/                                /var/www/html/setup/
COPY docker-entrypoint.sh                  /usr/local/bin/docker-entrypoint.sh

# Writable directories
RUN mkdir -p /var/www/html/web/sites/default/files \
             /var/www/html/config/sync \
             /var/drupal_private \
    && chown -R www-data:www-data \
         /var/www/html/web/sites/default/files \
         /var/www/html/config/sync \
         /var/drupal_private \
    && chmod -R 755 /var/www/html/web/sites/default/files \
    && chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]
