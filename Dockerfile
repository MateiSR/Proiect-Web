FROM php:8.2-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
  libpq-dev \
  libzip-dev \
  zip \
  && docker-php-ext-install pdo pdo_pgsql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Apache mod_rewrite for router
RUN a2enmod rewrite
# Allow .htaccess overrides
RUN echo '<Directory /var/www/html/>\n\
  AllowOverride All\n\
  </Directory>' > /etc/apache2/conf-available/override.conf && \
  a2enconf override
# Set the working directory
WORKDIR /var/www/html

# Install deps
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# Copy app files
COPY . .

# Change folder permissions
RUN chown -R www-data:www-data /var/www/html