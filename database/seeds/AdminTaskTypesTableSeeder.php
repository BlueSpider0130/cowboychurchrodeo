<?php

use Illuminate\Database\Seeder;

class AdminTaskTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = ['Bug', 'Enhancement', 'Proposal', 'Task'];
        
        foreach($types as $name)
        {
            App\AdminTaskType::create([
                'name' => $name
            ]);
        }
    }
}
