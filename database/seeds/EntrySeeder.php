<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class EntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run( Faker $faker )
    {
        if( \App\Contestant::count() < 1 )
        {
            $this->call(ContestantSeeder::class);
        }

        if( \App\Rodeo::count() < 1 )
        {
            $this->call(RodeoSeeder::class);
        }

        if( \App\Competition::count() < 1 )
        {
            $this->call(CompetitionSeeder::class);
        }

        foreach( \App\Rodeo::has('competitions')->get() as $rodeo )
        {
            $contestant = \App\Contestant::inRandomOrder()->first();

            factory( \App\RodeoEntry::class )->create([
                'contestant_id' => $contestant->id, 
                'rodeo_id' => $rodeo->id,
            ]);

            $competitionIds = $rodeo->competitions()->inRandomOrder()->pluck('id')->toArray();

            for ($i=0; $i < $faker->numberBetween(1, count($competitionIds)); $i++) 
            { 
                factory( \App\CompetitionEntry::class )->create([
                    'contestant_id' => $contestant->id, 
                    'competition_id' => $competitionIds[$i],
                ]);
            }

            if( $user = DeveloperSeeder::getDeveloperUser() )
            {
                $contestant = $user->contestants->first();
    
                factory( \App\RodeoEntry::class )->create([
                    'contestant_id' => $contestant->id, 
                    'rodeo_id' => $rodeo->id,
                ]);

                $competitions = $rodeo->competitions()->inRandomOrder()->get()->toArray();

                for ($i=0; $i < $faker->numberBetween(1, count($competitions)); $i++) 
                { 
                    factory( \App\CompetitionEntry::class )->create([
                        'contestant_id' => $contestant->id, 
                        'competition_id' => $competitionIds[$i],
                    ]);
                }                
            }
        }
    }
}
