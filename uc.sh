#!/bin/bash
#Чистка всего кэша
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear



