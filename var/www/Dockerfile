FROM php:8.1-apache

RUN apt-get update && apt-get install -y zip unzip curl libsqlite3-dev sqlite3
ADD https://raw.githubusercontent.com/mlocati/docker-php-extension-installer/master/install-php-extensions /usr/local/bin/
RUN chmod uga+x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions pdo_sqlite

COPY ./html /var/www/html
COPY ./src /var/www/src
COPY ./db /var/www/db

WORKDIR /var/www/html

EXPOSE 80

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]
