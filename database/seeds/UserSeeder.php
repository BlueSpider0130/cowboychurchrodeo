<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory( User::class, 2 )->states('admin')->create();
        factory( User::class, 10 )->create();
    }
}
