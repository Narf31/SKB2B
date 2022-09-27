#!/bin/bash
php artisan migrate && git pull && php artisan migrate