#!/bin/bash
# Check if docker-compose is running
if ! docker compose ps | grep -q "laravel.test"; then
    echo "Starting Docker containers..."
    docker compose up -d
fi

# Run artisan inside the container
docker compose exec laravel.test php artisan "$@"
