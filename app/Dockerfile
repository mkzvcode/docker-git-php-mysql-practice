FROM php:8.2-fpm
RUN docker-php-ext-install mysqli pdo_mysql
WORKDIR /var/www/html
COPY . /var/www/html
RUN chown -R www-data:www-data /var/www/html
EXPOSE 9000
CMD ["php-fpm"]
