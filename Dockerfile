FROM php:7.4-fpm
#Install git
RUN apt-get update \
    && apt-get install -y git
RUN docker-php-ext-install pdo pdo_mysql mysqli
#RUN a2enmod rewrite

#Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --install-dir=. --filename=composer
RUN mv composer /usr/local/bin/

WORKDIR /var/www/html

# install application dependencies
# COPY composer.json ./
# RUN composer install

COPY . /var/www/html/
#CMD [ "php", "-S", "0.0.0.0:8080", "-t", "public" ]
