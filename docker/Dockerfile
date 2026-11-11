FROM php:7.4-apache

RUN docker-php-ext-install pdo_mysql \
 && a2enmod rewrite

RUN printf 'date.timezone=UTC\nmemory_limit=256M\ndisplay_errors=On\nerror_reporting=E_ALL\n' \
    > /usr/local/etc/php/conf.d/bcp-dev.ini

COPY apache.conf /etc/apache2/sites-available/000-default.conf
