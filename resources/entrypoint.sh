#!/bin/bash

mv /tmp/resources/.env /var/www/html/.env
echo "Moved .env config file to docroot."

cd /var/www/html
echo "Changed directory to docroot."

composer install

chmod -R 777 /var/www/html/storage
chmod -R 777 /var/www/html/public
echo "Configured permissions to storage and public directories."

php artisan migrate --seed