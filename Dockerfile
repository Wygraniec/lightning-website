FROM php:8.2-apache

# Install mysqli
RUN docker-php-ext-install mysqli

COPY ./app/ /var/www/html/

RUN chown -R www-data:www-data /var/www/html/

EXPOSE 80
CMD ["apache2-foreground"]
