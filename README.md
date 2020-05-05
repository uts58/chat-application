## Messenger App using Laravel, Laravel Websockets, Laravel Echo, IMGBB api and Vue.JS 

In case my key got restricted, you need to get your api key from `imgbb.com`

## Get it up and running.

After you clone this project, do the following:

```bash
# go into the project
cd Messenger-App-VueJS-and-Laravel

# create a .env file
cp .env.example .env

# install composer dependencies
composer update

# install npm dependencies
npm install

# generate a key for your application
php artisan key:generate

# create a local MySQL database (make sure you have MySQL up and running)
mysql -u root

> create database chat_db;
> exit;

# add the database connection config to your .env file
DB_CONNECTION=mysql
DB_DATABASE=chat_db
DB_USERNAME=root
DB_PASSWORD=

# run the migration files to generate the schema
php artisan migrate

# change the BROADCAST_DRIVER in your .env to pusher
BROADCAST_DRIVER=pusher

# seed your databse with some users and messages
php artisan db:seed

# run laravel core
php artisan serve

#run laravel-websocket
php artisan websocket:serve

# run webpack and watch for changes
npm run watch
```
#In case you want to use original Pusher Service
* run ```composer remove beyondcode/laravel-websockets```
* delete `config/websockets.php`
* check the `config/broadcasting.php` and `resources/js/bootstrap.js` for instructions

#Main Project
https://github.com/AfikDeri/Messenger-App-VueJS-and-Laravel
