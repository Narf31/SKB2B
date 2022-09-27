@echo off
php artisan cache:clear & php artisan view:cache & php artisan route:clear & php artisan config:cache