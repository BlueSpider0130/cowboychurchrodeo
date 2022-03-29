<?php

use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if( \App\Organization::count() < 1 )
        {
            $this->call(OrganizationSeeder::class);            
        }

        foreach( \App\Organization::doesntHave('events')->pluck('id')->toArray() as $organizationId )
        {
            factory( \App\Event::class, 5 )->create([
                'organization_id' => $organizationId
            ]);
        } 
    }
}
