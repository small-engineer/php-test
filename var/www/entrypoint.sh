#!/bin/bash

rm touch /var/www/db/database.sqlite
touch /var/www/db/database.sqlite
chown -R www-data:www-data /var/www/db
chmod -R 777 /var/www/db

# Create tables if they don't exist
php /var/www/db/create_tables.php

# Start Apache
apache2-foreground
