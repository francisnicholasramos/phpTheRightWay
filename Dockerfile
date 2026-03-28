FROM php:8.2-apache

RUN apt-get update && apt-get install -y libpq-dev git && \
    docker-php-ext-install pdo pdo_pgsql

RUN a2enmod rewrite

# Point DocumentRoot to public/ and enable AllowOverride for .htaccess
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' \
        /etc/apache2/sites-available/*.conf \
        /etc/apache2/conf-available/*.conf && \
    sed -i 's/AllowOverride None/AllowOverride All/g' \
        /etc/apache2/apache2.conf \
        /etc/apache2/sites-available/*.conf

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

COPY . .

RUN chmod -R 755 /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
