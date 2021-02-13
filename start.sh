#!/bin/bash

echo "======> Copying sample_database.php to database.php"
cp ./application/config/sample_database.php ./application/config/database.php 
echo "DONE"
echo "\n"

echo "======> Going into the php docker image"
docker exec -it php-httpd /bin/bash
echo "======> Running composer install"
composer install
echo "DONE"
echo "\n"
