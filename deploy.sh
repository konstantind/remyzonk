#!/bin/bash

cd /root/remyzonk || exit

git pull origin main

docker compose -f docker-compose.prod.yml down
docker compose -f docker-compose.prod.yml up -d --build nginx app schedule db redis

echo "✅ Деплой завершён"
