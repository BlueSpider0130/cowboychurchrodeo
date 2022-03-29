<?php

use Illuminate\Database\Seeder;

class AdminTaskStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = ['In progress', 'Complete', 'On hold'];
        
        foreach($statuses as $name)
        {
            App\AdminTaskStatus::create([
                'name' => $name
            ]);
        }
    }
}
