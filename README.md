## Requirements

* npm
* composer
* laravel
* vue.js
* mysql/mariadb
* apache/nginx

## Installation

* cd into project folder
* run `composer install`
* run `npm install`
* make a copy of .env.example as .env & setup database information
* run `php artisan migrate --seed`
* build frontend `npm run dev`
* start laravel server `php artisan serve`
