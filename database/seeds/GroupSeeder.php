<?php

use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
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

        foreach( \App\Organization::doesntHave('groups')->pluck('id')->toArray() as $organizationId )
        {
            factory( \App\Group::class, 5 )->create([
                'organization_id' => $organizationId
            ]);
        }
    }
}
