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

- To solve the Mass Assignment Error
- Add the following field at the top of your Todo model 
```php
protected $guarded = [];
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
$table->boolean('completed')->default('0');
$table->integer('user_id')->nullable();
$table->timestamps();
```

- Take note that the users migration has been created for your already
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

# TodosController

- Add the following code to your index method in the TodosController
```php
return response()->json(Todo::all());
```

- Add the following code to your show method in the TodosController
```php
return response()->json($todo->with('owner')->get()->where('id', '=', $todo->id));
```

- Add the following code into your store method
```php
$todo = Todo::create([
    'task' => $request->task,
    'completed' => $request->completed ? $request->completed : 0,
    'user_id' => User::all()->random(1)->pluck('id')[0]
]);

return response()->json(['message' => 'Todo successfully created']);
```

-- Add the following code into your destroy method
```php
$todo->delete();

return response()->json(['message' => "Todo with id {$todo->id} has been successfully deleted"]);
```

-- Add the following code to the update method
```php
if ($request->task)
    $todo->task = $request->task;
if ($request->completed)
    $todo->completed = $request->completed;

$todo->save();

return response()->json($todo);
```

# UsersController

- Add the following code to your index method in the UsersController
```php
return response()->json(User::all()->first()->with('todos')->get());
```

- Add the following code to your show method in the UsersController
```php
return response()->json($user->with('todos')->get()->where('id', '=', $user->id));
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

- Add the following code to your destroy method
```php
$user->delete();

return response()->json(['message' => "User with id {$user->id} has been successfully deleted"]);
```

- Add the following code to the update method
```php
if ($request->name)
    $user->name = $request->name;
if ($request->email)
    $user->email = $request->email;

$user->save();

return response()->json($user);
```

## Step 9 restful API Testing Using Postman
- HTTP Methods that correspond to methods in your controllers 
delete, put, post and get

- get - http://{-your domain-}/api/todos
- get - http://{-your domain-}/api/todos/1
- post - http://{-your domain-}/api/todos - pass it some body data -> json format
```php
task: Upload app to github
```
        
- delete - http://{-your domain-}/api/todos/1
- put - http://{-your domain-}/api/todos/1 - pass it some body data -> ensure that you use the x-www-form-urlencoded data
```php
task: Test this api
completed: 1
```        
        
- get - http://{-your domain-}/api/users
- get - http://{-your domain-}/api/users/1
- post - http://{-your domain-}/api/todos - pass it some body data -> json format
```php
name: Laravel Namibia
email: laranamibia@gmail.com
```

- delete - http://{-your domain-}/api/users/1
- put - http://{-your domain-}/api/todos/1 - pass it some body data -> ensure that you use the x-www-form-urlencoded data
```php
name: Laravel SA
email: larasa@gmail.com
```