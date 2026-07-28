#!/bin/sh
php artisan migrate --force
php artisan db:seed --class=DatabaseSeeder --force 2>/dev/null || true
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
