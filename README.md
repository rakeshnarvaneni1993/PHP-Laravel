# PHP-Laravel-Angular-4
A sample PHP-Laravel setup for Angular 4

Steps to Setup:

Database Setup: 

1. Open SQL command Shell.
2. Create a database in SQL using command "create database <name of the database>"
2. Use the created database by running 'use <name of the database>'
3. Create tables by running 'CREATE TABLE table_name (
    column1 datatype,
    column2 datatype,
    column3 datatype,
   ....
);'
4. Using SQL Workbench start the server on port: 3306

Laravel Setup:

1. Install composer from "https://getcomposer.org/download/"
2. Install Laravel using Composer by running command: "composer global require "laravel/installer=~1.1"
3. Create a new Laravel project by running "Laravel new <name_of_project>". This creates a new Laravel project. 
4. Change the following details in the .env file:
            a. DB_CONNECTION=mysql
            b. DB_HOST= 127.0.0.1
            c. DB_PORT=3306
            d. DB_DATABASE=<name of the database>
            e. DB_USERNAME=root
            f. DB_PASSWORD=<password to access the database>
5. Inside config folder change the following details inside database.php
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', '<name of the database>'),
            'username' => env('DB_USERNAME', 'root@localhost'),
            'password' => env('DB_PASSWORD', '<password to access the database>'),
            'unix_socket' => env('DB_SOCKET', 'MYSQL'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
        
6. Inside the Laravel project run "PHP artisan migrate" command
7. Create a new Model using "php artisan make:model <name of the Model> -m
8. Inside the model file place this code: "protected $table = "<Name of the table you want to access>";"
9. Inside Routes foler add a new Route inside api.php
      Sample: Route::get('/<needed url>', '<any name of controller>Controller@index');
      Example:Route::get('/posts', 'ExampleController@index');
10. Inside app/http create new file with the same name as you gave above.
      Example: ExampleController.php
11. Inside the file write this following code:
                      <?php

                namespace App\Http\Controllers;

                use Illuminate\Http\Request;

                class ExampleController extends Controller
                {
                    public function index()
    {
                     $examples = <name of the MOdel you created in step: 7>::get();

                        return response()->success(compact('examples'));
    
    }
                }
12. Start the Laravel server using php artisan serve
13. Access the URL by URL: localhost:8000/api/<name of the URL created in step: 9
14 You should see the Json format of the data you put in the tables.
