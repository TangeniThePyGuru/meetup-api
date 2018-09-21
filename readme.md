<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## Basic Laravel Restful API
- This application serves to demonstrate the basics of Laravel Restful Api in 10 minutes by creating a todo app

## Step 1 : Create The Todo Model
- The artisan command below creates a model of the name Todo alongside a migration for the database schema
```php
php artisan make:model Todo -m
```


## Step 2 : Create the migration for the model
- The artisan command below crerats a controller of the name TodosController and binds it to the Todo model
- The --api parameter adds the methods that a generally used in an api to the created controller
```php
php artisan make:controller TodosController --model=Todo --api 
```

- Navigate to the created migration and add the following code to the up() method
```php
$table->increments('id');
$table->string('task');
$table->boolean('completed');
$table->integer('user_id')->nullable();
$table->timestamps();
```

- To create your tables, execute the following artisan command to run the migrations
```php
php artisan migrate
```

## Step 3 : Define your relationships
- go to the Todo model and define the relationship that deals with the owner of the task
- we have a one to many relation: one Todo belongs to one User; One user has many Todos
- The code below creates an owner relation in the Todo class
```php
public function owner()
{
    return $this->belongsTo(User::class);
}
```
- The code below links a user to his Todos
```php
public function todos()
{
    return $this->hasMany(Todo::class);
}
```


