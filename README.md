php artisan serve 
composer require barryvdh/laravel-dompdf
php artisan migrate:fresh
php artisan db:seed

# Feature Require Verification?

login NO
browse workshops NO
enroll YES
payments YES
trainer access YES

php artisan make:migration create_workshops_table
php artisan make:model Workshop

php artisan make:request StoreWorkshopRequest
php artisan make:request UpdateWorkshopRequest

php artisan make:controller API/V1/WorkshopController

# Clear cache for laravel
php artisan optimize:clear
php artisan route:clear
php artisan config:clear
php artisan cache:clear
