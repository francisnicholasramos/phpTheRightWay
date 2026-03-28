FROM php:8.2-apache
RUN apt-get update && apt-get install -y libpq-dev git && docker-php-ext-install pdo pdo_pgsql
RUN a2enmod rewrite
WORKDIR /var/www/html/public
COPY public .
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader
RUN chmod -R 755 /var/www/html
EXPOSE 80
CMD ["apache2-foreground"]
