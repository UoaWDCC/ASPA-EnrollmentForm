#!/bin/bash

echo "======> Copying sample_database.php to database.php"
cp ./application/config/sample_database.php ./application/config/database.php 
echo "DONE"
read -p 'Press Enter to continue...' var

echo "======> Running composer install"
docker exec aspa-enrollmentform_php-httpd_1 composer install
echo "DONE"
read -p 'Press Enter to continue...' var

echo "======================================"
read -p 'Press Enter to exit...' var