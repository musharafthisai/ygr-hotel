FROM php:8.2-apache

RUN docker-php-ext-install pdo_mysql mysqli && a2enmod rewrite

COPY . /var/www/html/

RUN mv /var/www/html/htaccess /var/www/html/.htaccess 2>/dev/null; \
    chown -R www-data:www-data /var/www/html/assets/img/items/ 2>/dev/null; \
    chmod -R 755 /var/www/html/assets/img/items/ 2>/dev/null

ENV APP_ENV=production
EXPOSE 80
CMD ["apache2-foreground"]
