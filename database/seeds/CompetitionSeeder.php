<?php

use Illuminate\Database\Seeder;

class CompetitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Competitions in rodeos...

        if( \App\Rodeo::count() < 1 )
        {
            $this->call(RodeoSeeder::class);
        }

        foreach( \App\Organization::has('rodeos')->get() as $organization )
        {
            if( $organization->events()->count() < 1 )
            {
                $this->call(EventSeeder::class);
            }

            if( $organization->groups()->count() < 1 )
            {
                $this->call(GroupSeeder::class);
            }

            foreach( $organization->rodeos()->doesntHave('competitions')->get() as $rodeo )
            {
                foreach( \App\Group::where('organization_id', $organization->id)->pluck('id')->toArray() as $groupId )
                {
                    foreach ( \App\Event::where('organization_id', $organization->id)->pluck('id')->toArray() as $eventId ) 
                    {
                        factory( \App\Competition::class )
                        ->states(['with-instances'])
                        ->create([
                            'organization_id' => $organization->id, 
                            'rodeo_id' => $rodeo->id, 
                            'event_id' => $eventId, 
                            'group_id' => $groupId
                        ]);
                    }
                }                
            }
        }


        // Competitions not part of rodeo...

        // ...
    }
}
