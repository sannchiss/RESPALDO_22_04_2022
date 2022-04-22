echo "Borrando DB       => 10%"
dropdb prisaum_db
echo "Creando DB        ===> 30%"
createdb prisaum_db
echo "Limpiando REDIS   =============> 40%"
redis-cli flushall
echo "Levantando migraciones   =============> 70%"
php artisan migrate --step --seed
echo "Proyecto limpiado =====================>100%"