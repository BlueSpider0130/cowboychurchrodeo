<?php

use Illuminate\Database\Seeder;

class DevelopmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DEVELOPER USER
        //
        $this->call(DeveloperSeeder::class);
        $dev = DeveloperSeeder::getDeveloperUser();

        // OTHER USERS
        //
        factory( \App\User::class )->create([
            'email' => "kevin@happenstanceranch.com",
            'password' => '$2y$10$qNO/XsP1IWveSGBamLmTMe9CCdo/a0GsLKYIupiQKvgr7dvjWUKJ.',
            'admin' => true,
            'first_name' => "Kevin",
            'last_name' => "",
        ]);

        // ORGANIZATION
        //
        $organization = factory( \App\Organization::class )->create([
            'name' => 'A1 Test Organization'
        ]);

        // CONTESTANTS   (for developer user)
        //
        $devContestants = factory( \App\Contestant::class, 3)
            ->create([ 'organization_id' => $organization->id ])
            ->each( function($c) use ($dev) {
                $c->users()->attach( $dev->id );
            });


        // CONTESTANTS   (from old rodeo)
        //
        foreach ( json_decode(file_get_contents(__DIR__.'/data/contestants.json'), true) as $data )
        {
            foreach( array_keys($data) as $key )
            {
                $keys = [
                    'first_name', 
                    'last_name', 
                    'birthdate', 
                    'address_line_1', 
                    'address_line_2', 
                    'city', 
                    'state', 
                    'postcode', 
                ];        
                          
                if( !in_array($key, $keys) )
                {
                    unset($data[$key]);
                }
            }

            $data['organization_id'] = $organization->id;

            $contestant = factory( \App\Contestant::class )->create( $data );

            if( mt_rand(0, 5) < 1 )
            {
                $user = factory( \App\User::class )->create();
                $user->contestants()->attach($contestant->id);
            }
        }            

        // DOCUMENTS
        //
        $documents = factory( \App\Document::class, 3 )->create([ 'organization_id' => $organization->id ]);
        

        // GROUPS
        //
        // $groups = factory( \App\Group::class, 3 )->create([ 'organization_id' => $organization->id ]);
        $names = ['PeeWee','7 & Under','10 & Under','14 & Under','19 & Under'];
        foreach($names as $name)
        {
           factory( \App\Group::class )->create([ 'organization_id' => $organization->id, 'name' => $name ]);
        }
        $groups = $organization->groups()->get();


        // EVENTS  (from old rodeo)
        //
        foreach ( json_decode(file_get_contents(__DIR__.'/data/events.json'), true) as $data )
        {
            if( isset($data['name'])  &&  false === stripos($data['name'], 'Pee') )
            {
                foreach( array_keys($data) as $key )
                {
                    $keys = [
                        'organization_id',
                        'name', 
                        'description',
                        'team_roping', 
                        'result_type'
                    ];        
                              
                    if( !in_array($key, $keys) )
                    {
                        unset($data[$key]);
                    }
                }

                $data['organization_id'] = $organization->id;

                if( false !== stripos($data['name'], 'Team') )
                {
                    $data['team_roping'] = true;
                }

                factory( \App\Event::class )->create( $data );
            }
        }

        $events = App\Event::all();


        // SERIES
        //
        $series = factory( \App\Series::class )->create([ 
            'organization_id' => $organization->id,
            'starts_at' => \Carbon\Carbon::now()->subDays(120),
            'ends_at' => \Carbon\Carbon::now()->addDays(120)
        ]);

        // attach documents to series
        if( isset($documents) )
        {
            foreach( $documents as $document )
            {
                $series->documents()->attach( $document->id );
            }
        }


        // MEMBERSHIPS
        //
        $devContestantIds = $dev->contestants->pluck('id')->toArray();

        $count = 1;
        foreach( $dev->contestants()->where('organization_id', $organization->id)->get() as $contestant )
        {
            if( $count < 3 )
            {
                \App\Membership::create([
                    'contestant_id' => $contestant->id,
                    'series_id' => $series->id,
                    'paid' => 1 == $count ? true : false,
                ]);       
            }
            else
            {
                \App\Membership::where('contestant_id', $contestant->id)->delete();
            }

            $count++;     
        }

        $availableContestantIds = \App\Contestant::whereNotIn('id', $devContestantIds)
                                    ->where('organization_id', $organization->id)
                                    ->pluck('id')
                                    ->toArray();

        shuffle($availableContestantIds);

        $availableContestantIds = array_values($availableContestantIds);

        for ($i=0; $i < round(count($availableContestantIds)/2); $i++) 
        { 
                \App\Membership::create([
                    'contestant_id' => $availableContestantIds[$i],
                    'series_id' => $series->id,
                    'paid' => mt_rand(0,1) > 0 ? true : false,
                ]);
        }


        // RODEOS
        //
        factory( \App\Rodeo::class )->create([ 
            'organization_id' => $organization->id, 
            'series_id' => $series->id,
            'name' => 'Rodeo 1',// (ended)',
            'starts_at' => \Carbon\Carbon::now()->startOfDay()->subDays(12),
            'ends_at' => \Carbon\Carbon::now()->startOfDay()->subDays(10)
        ]);

        factory( \App\Rodeo::class )->create([ 
            'organization_id' => $organization->id, 
            'series_id' => $series->id,
            'name' => 'Rodeo 2',// (registration closed)',
            'starts_at' => \Carbon\Carbon::now()->startOfDay()->addDays(10),
            'ends_at' => \Carbon\Carbon::now()->startOfDay()->addDays(12), 
            'closes_at' => \Carbon\Carbon::now()->startOfDay()->subDays(5),
        ]);

        factory( \App\Rodeo::class )->create([ 
            'organization_id' => $organization->id, 
            'series_id' => $series->id,
            'name' => 'Rodeo 3',// (registration not open yet)',
            'starts_at' => \Carbon\Carbon::now()->startOfDay()->addDays(13),
            'ends_at' => \Carbon\Carbon::now()->startOfDay()->addDays(15), 
            'opens_at' => \Carbon\Carbon::now()->startOfDay()->addDays(5),
            'closes_at' => \Carbon\Carbon::now()->startOfDay()->addDays(6),
        ]);

        factory( \App\Rodeo::class )->create([ 
            'organization_id' => $organization->id, 
            'series_id' => $series->id,
            'name' => 'Rodeo 4',// (registration open)',
            'starts_at' => \Carbon\Carbon::now()->startOfDay()->addDays(17),
            'ends_at' => \Carbon\Carbon::now()->startOfDay()->addDays(19), 
            'opens_at' => \Carbon\Carbon::now()->startOfDay()->subDays(2),
            'closes_at' => \Carbon\Carbon::now()->startOfDay()->addDays(2),            
        ]);
        
        factory( \App\Rodeo::class )->create([ 
            'organization_id' => $organization->id, 
            'series_id' => $series->id,
            'name' => 'Rodeo 5',// (in progress)',
            'starts_at' => \Carbon\Carbon::now()->startOfDay()->subDays(1),
            'ends_at' => \Carbon\Carbon::now()->startOfDay()->addDays(1)
        ]);


        // COMPETITIONS
        //
        $groupsForCompetitions = $groups->take(2);
        $eventsForCompetitions = [];
        $eventsForCompetitions[] = \App\Event::where('team_roping', 1)->inRandomOrder()->limit(1)->first();
        $eventsForCompetitions[] = \App\Event::where('team_roping', 0)->inRandomOrder()->limit(1)->first();
        $eventsForCompetitions = collect($eventsForCompetitions);

        foreach ( \App\Rodeo::where('series_id', $series->id)->get() as $rodeo ) 
        {
            foreach( $groupsForCompetitions as $group )
            {
                foreach ($eventsForCompetitions as $event) 
                {
                    if( mt_rand(0, 4) > 0 )
                    {
                        $competition = factory( \App\Competition::class )->create([
                            'organization_id' => $organization->id, 
                            'rodeo_id' => $rodeo->id, 
                            'group_id' => $group->id, 
                            'event_id' => $event->id,
                            'entry_fee' => mt_rand(30, 100),
                            'max_entries_per_contestant' => $event->team_roping ? 3 : 1
                        ]);

                        // instances
                        $days = $rodeo->starts_at->diffInDays($rodeo->ends_at);
                        $day = $rodeo->starts_at->startOfDay();

                        for ($i=0; $i < $days; $i++) 
                        { 
                            factory( \App\CompetitionInstance::class )->create([
                                'competition_id' => $competition->id,
                                'starts_at' => $day
                            ]);  

                            $day = $day->copy()->addDays(1);
                        }               
                    }
                }
            }
        }


        // ENTRIES
        //
        $count = 0;
        foreach( $devContestants->take(2) as $contestant )
        {
            $rodeos = App\Rodeo::with(['competitions', 'competitions.event', 'competitions.instances'])->get();

            foreach( $rodeos as $rodeo )
            {
                // Rodeo entry
                if( $count < 1 )
                {
                    \App\RodeoEntry::create([
                        'contestant_id' => $contestant->id,
                        'rodeo_id' => $rodeo->id,
                    ]);
                }

                // Competition entries
                $competitions = $rodeo->competitions->take(2);

                $headerEntries = [];
                $heelerEntries = [];

                foreach( $competitions as $competition )
                {
                    if( $competitions->count() < 3  ||  mt_rand(0, 3) )
                    {
                        $states = mt_rand(0, 1) ? [] : ['no-fee', 'no-score'];

                        $position = null;

                        if( $competition->event->team_roping )
                        {
                            if( $headerEntries )
                            {
                                $position = 'header';
                            }

                            elseif( !$heelerEntries )
                            {
                                $position = 'heeler';
                            }

                            else 
                            {
                                $position = mt_rand(0,1) ? 'header' : 'heeler';
                            }
                        }

                        $instancesArray = $competition->instances->toArray();
                        $instanceId = $instancesArray[array_rand($instancesArray)]['id'];

                        $entry = factory( \App\CompetitionEntry::class )->states($states)->create([
                            'competition_id' => $competition->id, 
                            'contestant_id' => $contestant->id,
                            'instance_id' => $instanceId,
                            'position' => $position
                        ]);
             
                        if( 'header' == $entry->position )
                        {
                            $headerEntries[] = $entry;
                        }

                        if( 'heeler' == $entry->position )
                        {
                            $heelerEntries[] = $entry;
                        }
                        

                    }
                }

                // team entries
                if( count($headerEntries) > 0 )
                {
                    unset($headerEntries[array_rand($headerEntries)]);
                    while( count($headerEntries) > 0  &&  count($heelerEntries) > 0 )
                    {
                        $headerKey = array_rand($headerEntries);
                        $heelerKey = array_rand($heelerEntries);

                        \App\TeamRopingEntry::create([
                            'header_entry_id' => $headerEntries[$headerKey]->id,
                            'heeler_entry_id' => $heelerEntries[$heelerKey]->id,
                        ]);

                        unset($headerEntries[$headerKey]);
                        unset($heelerEntries[$heelerKey]);
                    }
                }
            }
        }


return null;
        //additional
        $organizations = factory( \App\Organization::class, 4 )->create();
        foreach( $organizations as $organization )
        {
            $events = factory(\App\Event::class, 3)->create([ 'organization_id' => $organization->id ]);
            $groups = factory(\App\Group::class, 2)->create([ 'organization_id' => $organization->id ]);
            
            $series = factory( \App\Series::class )->create([ 
                'organization_id' => $organization->id,
                'starts_at' => \Carbon\Carbon::now()->subDays(mt_rand(100, 150)),
                'ends_at' => \Carbon\Carbon::now()->addDays(mt_rand(100, 150))
            ]);

            $rodeos = [];

            $start = \Carbon\Carbon::now()->subDays(10,30);
            $end = $start->copy()->addDays(1);
            $rodeos[] = factory( \App\Rodeo::class )->create([ 
                'organization_id' => $organization->id, 
                'series_id' => $series->id,
                'starts_at' => $start,
                'ends_at' => $end
            ]);

            $start = \Carbon\Carbon::now()->addDays(10,30);
            $end = $start->copy()->addDays(1);
            $rodeos[] = factory( \App\Rodeo::class )->create([ 
                'organization_id' => $organization->id, 
                'series_id' => $series->id,
                'starts_at' => $start,
                'ends_at' => $end
            ]);

            foreach ($rodeos as $rodeo) 
            {
                foreach($groups as $group)
                {
                    foreach( $events as $event )
                    {
                        factory( \App\Competition::class )->create([
                            'organization_id' => $organization->id, 
                            'rodeo_id' => $rodeo->id, 
                            'event_id' => $event->id,
                            'group_id' => $group->id, 
                        ]);
                    }
                }
            }
        }


    }
}
