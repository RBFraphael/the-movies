FROM wordpress:php8.3-apache

RUN apt-get update
RUN apt-get upgrade -y
RUN apt-get install -y nano

RUN a2enmod rewrite

RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
RUN chmod +x wp-cli.phar
RUN mv wp-cli.phar /usr/local/bin/wp

WORKDIR /var/www/html
