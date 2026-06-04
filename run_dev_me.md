composer install
npm install

php artisan migrate
php artisan migrate:fresh --seed
php artisan indoregion:publish
php artisan db:seed
php artisan storage:link
npm run build

php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Restart serve
php artisan serve --host=0.0.0.0 --port=8085