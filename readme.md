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


## Step 2 : Create the controllers
- The artisan command below crerats a controller of the name TodosController and binds it to the Todo model
- The --api parameter adds the methods that a generally used in an api to the created controller
```php
php artisan make:controller TodosController --model=Todo --api 
```

- Creates the User Controller
```php
php artisan make:controller UsersController  --model=User --api
```

## Step 4 : Create the migration for the Todo model
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

## Step 5 : Define your relationships
- go to the Todo model and define the relationship that deals with the owner of the task
- we have a one to many relation: one Todo belongs to one User; One user has many Todos
- The code below creates an owner relation in the Todo class
```php
public function owner()
{
    return $this->belongsTo(User::class, 'user_id');
}
```
- The code below links a user to his Todos
```php
public function todos()
{
    return $this->hasMany(Todo::class);
}
```

## Step 6 : Define Model factories
- paste the following code into the TodoFactory created earlier
```php
$users = \App\User::all()->random(1)->pluck('id')->values();

return [
    'task' => $faker->sentence,
    'completed' => $faker->boolean(),
    'user_id' => $users[0]
];
```

- Take note the UserFactory already comes predefined for you and ready to use, so no need to create it
- It can be seen under the database/factories directory 

## Step 7 : Seeding your database 
- Laravel comes with many different approaches to seed your database, 
    - ranging from laravel tinker, 
    - or using the artisan command below
    ```php
      php artisan db:seed
    ```
- We will use tinker - is a command line interface tool for manipulating the database
- The below artisan command opens the tinker CLI
```php
php artisan tinker
```
- Within the tinker environment
- Seed the user table with as many rows of data as you want in this case 20 users
```php
factory(App\User::class, 20)->create()
```
- Seed the todo table with 40 rows of data
```php
factory(App\Todo::class, 40)->create()
```

## Step 7 : Api Routes
- Add the following code to your API routes
```php
Route::resource('todos', 'TodosController');
```

## Step 8 : controller implementation

TodosController

- Add the following code to your index method in the TodosController
```php
return response()->json(Todo::all(), 200);
```

- Add the following code to your show method in the TodosController
```php
return response()->json($todo->with('owner')->get()->where('id', '=', $todo->id), 200);
```

- Add the following code into your store method
```php
$todo = Todo::create([
    'task' => $request->task,
    'completed' => $request->completed,
    'user_id' => User::all()->random(1)->pluck('id')[0]
]);

return response()->json(['message' => 'Todo successfully created']);

UsersController

- Add the following code to your index method in the UsersController
```php
return response()->json(User::all()->first()->with('todos')->get(), 200);
```

- Add the following code to your show method in the UsersController
```php
return response()->json($user->with('todos')->get()->where('id', '=', $user->id), 200);
```

- Add the following code into your store method
```php
$todo = User::create([
    'name' => $request->name,
    'email' => $request->email,
    'password' => bcrypt('secret')
]);

return response()->json(['message' => 'User successfully created']);
```

## Step 9 restful API Testing Using Postman
- http://{-your domain-}/api/todos
- http://{-your domain-}/api/todos/1
- http://{-your domain-}/api/users
- http://{-your domain-}/api/users/1

## Step 10 Solving the Mass Assignment Error
- Add the following field at the top of each of your Todo model 
```php
protected $guarded = [];
```