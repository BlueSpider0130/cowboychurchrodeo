<?php

use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //factory(App\Organization::class, 2)->create();
        //factory(App\Organization::class, 2)->states('with-account-details')->create();

        factory(App\Organization::class)->states('with-account-details')->create();

    }
}
