# e-commerce_api
API's Of small e-commerce application

Staps in larval creat projcet

First Install 
compser for web 

Steps of creating a laraval Projects
1. create projects of larval 
	1.1 With specific Version
        composer create-project --prefer-dist laravel/laravel blog "5.8.35"
	1.2 With latest version
	    composer global require "laravel/installer" laravel new blog
2. Run the Project 
   php artisan serve
3. Check Database in .env file on this if data base is not set then update the data base name and its credential
4. Run migration command
   php artisan migrate
5. craete a migration 
   php artisan make:migration create_users_table
6. create a controller
   php artisan make:controller controllername
7. Cretae a model
   php artisan make:model modelname
8. create Seeder
   php artisan make:seeder UsersTableSeeder
9. Run seeder command
   php artisan db:seed
10. Create a controller
	php artisan make:controller Admin/Dashboard
11. For mail send
    php artisan make:mail Product