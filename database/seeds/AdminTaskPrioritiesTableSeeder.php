<?php

use Illuminate\Database\Seeder;

class AdminTaskPrioritiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $priorities = ['Trivial', 'Minor', 'Major', 'Critical', 'Blocker'];

        foreach ($priorities as $name) 
        {
            App\AdminTaskPriority::create([
                'name' => $name
            ]);
        }
    }
}
