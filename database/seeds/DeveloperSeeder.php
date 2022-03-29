<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Organization;
use App\UserLevel;
use App\Contestant;

class DeveloperSeeder extends Seeder
{
    static function getDeveloperUser()
    {
        if( env('DEVELOPER_EMAIL') )
        {
            return env('DEVELOPER_EMAIL') ? User::where('email', env('DEVELOPER_EMAIL'))->first() : null;
        }
        else 
        {
            return User::where('email', "dev@test.test")->first();
        }
    }

    /**
     * Run the database seeds to seed DEVELOPER user accounts.
     *
     * @return void
     */
    public function run()
    {
        // If not user acccount with developer email, 
        // then create user account using the developer info in .env

        if( null === self::getDeveloperUser() )
        {
            if( env('DEVELOPER_EMAIL') )
            {
                $data['email'] = env('DEVELOPER_EMAIL');

                if( $firstName = env('DEVELOPER_FIRST_NAME') )
                {
                    $data['first_name'] = $firstName;
                }

                if( $lastName = env('DEVELOPER_LAST_NAME') )
                {
                    $data['last_name'] = $lastName;
                }

                if( $password = env('DEVELOPER_PASSWORD') )
                {
                    $data['password'] = $password;
                }

                $user = factory(User::class)->states('admin')->create($data);
            }
            else 
            {
                $user = factory(User::class)->states('admin')->create(['email' => 'dev@test.test']);
            }
        }
    }
}
