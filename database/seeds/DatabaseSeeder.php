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
        $this->call(AdminTaskPrioritiesTableSeeder::class);
        $this->call(AdminTaskStatusesTableSeeder::class);
        $this->call(AdminTaskTypesTableSeeder::class);




        // $this->call(DeveloperSeeder::class);
        // $this->call(UserSeeder::class);
        // $this->call(OrganizationSeeder::class);
        // $this->call(ContestantSeeder::class);
        // $this->call(DocumentSeeder::class);
        // $this->call(EventSeeder::class);
        // $this->call(GroupSeeder::class);
        // $this->call(SeriesSeeder::class);
        // $this->call(RodeoSeeder::class);
        // $this->call(CompetitionSeeder::class);
        // $this->call(EntrySeeder::class);
        

        $this->call( DevelopmentSeeder::class );


        $this->command->line('Cleanup storage...');
        \Artisan::call('storage:clean');        
    }
}
