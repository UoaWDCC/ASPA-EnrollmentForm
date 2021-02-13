#!/bin/bash

echo "======> Copying sample_database.php to database.php"
cp ./application/config/sample_database.php ./application/config/database.php 
echo "DONE"
echo "\n"

echo "======> Running composer install"
docker exec -it php-httpd '/bin/bash composer install'
echo "DONE"
echo "\n"
