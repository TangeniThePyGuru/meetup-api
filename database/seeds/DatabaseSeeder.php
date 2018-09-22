<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // removes previously created data
        \App\User::truncate();
        \App\Todo::truncate();

        // seeds the database
        factory(\App\User::class, 20)->create();
        factory(\App\Todo::class, 60)->create();
    }
}
