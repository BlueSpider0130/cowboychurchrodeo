<?php

use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
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

        foreach( \App\Organization::doesntHave('documents')->pluck('id')->toArray() as $organizationId )
        {
            factory( \App\Document::class, 3 )->create([
                'organization_id' => $organizationId
            ]);
        }
    }
}
