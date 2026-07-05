#!/bin/bash

# Clear cache
php artisan optimize:clear

# Cache configuration, routing, and views for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations (forces execution without confirmation in production)
php artisan migrate --force

# Start Apache in the foreground
apache2-foreground
