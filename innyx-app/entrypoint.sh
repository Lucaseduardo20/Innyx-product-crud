#!/bin/sh

until php artisan migrate:status > /dev/null 2>&1; do
  echo "⏳ Aguardando o banco de dados..."
  sleep 2
done

HAS_MIGRATIONS=$(php artisan migrate:status --no-ansi | grep -cE '\|\s+Ran\s+\|')

if [ "$HAS_MIGRATIONS" -eq "0" ]; then
  echo "🚀 Rodando migrations e seeds..."
  php artisan migrate --seed --force
else
  echo "✅ Migrations já rodadas, pulando..."
fi

exec "$@"
