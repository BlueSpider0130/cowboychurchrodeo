<?php

use Illuminate\Database\Seeder;

class RodeoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Rodeos in series 
        if( \App\Series::count() < 1 )
        {
            $this->call(SeriesSeeder::class);
        }

        foreach( \App\Series::doesntHave('rodeos')->get() as $series )
        {
            $this->createRodeos([ 'organization_id' => $series->organization_id, 'series_id' => $series->id ]);
        }
        
        // Rodeos not in series 
        if( \App\Organization::count() < 1 )
        {
            $this->call(OrganizationSeeder::class);            
        }                

        // foreach( \App\Organization::all() as $organization )
        // {
        //     $this->createRodeos([ 'organization_id' => $organization->id ]);
        // }    
    }

    private function createRodeos( $data = [] )
    {
        factory( \App\Rodeo::class )->create( $data );
        factory( \App\Rodeo::class )->states(['with-entry-fee'])->create( $data );
        factory( \App\Rodeo::class )->states(['tba'])->create( $data );
        factory( \App\Rodeo::class )->states(['tba-end'])->create( $data );
        factory( \App\Rodeo::class )->states(['scheduled'])->create( $data );
        factory( \App\Rodeo::class )->states(['ended'])->create( $data );
        factory( \App\Rodeo::class )->states(['open'])->create( $data );
        factory( \App\Rodeo::class )->states(['closed'])->create( $data );
        factory( \App\Rodeo::class )->states(['open-scheduled'])->create( $data );
    }    
}
