<?php

use Illuminate\Database\Seeder;

class ContestantSeeder extends Seeder
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

        foreach( \App\Organization::doesntHave('contestants')->pluck('id')->toArray() as $organizationId )
        {
            // Contestants with no user account 
            factory( \App\Contestant::class, 5 )->create([
                'organization_id' => $organizationId
            ]);

            // Contestants with user account 
            factory( \App\Contestant::class, 5 )
            ->states([ 'with-user' ])
            ->create([
                'organization_id' => $organizationId
            ]);

            // Add contestant to developer 
            if( $user = DeveloperSeeder::getDeveloperUser() )
            {
                $contestant = factory( \App\Contestant::class )
                                ->create([
                                    'organization_id' => $organizationId
                                ]);                
                $contestant->users()->attach($user->id);
            }
        }
    }
}
