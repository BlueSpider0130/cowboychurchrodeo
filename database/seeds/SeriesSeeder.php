<?php

use Illuminate\Database\Seeder;

class SeriesSeeder extends Seeder
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

        foreach( \App\Organization::doesntHave('series')->pluck('id')->toArray() as $organizationId )
        {
            $data = [ 'organization_id' => $organizationId ];

            factory( \App\Series::class )->create( $data );
            // factory( \App\Series::class )->states(['with-membership-fee'])->create( $data );
            // factory( \App\Series::class )->states(['tba'])->create( $data );
            // factory( \App\Series::class )->states(['tba-end'])->create( $data );
        }      
    }
}
