<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class DeveloperController extends Controller
{

    private $devEnvironments = [ 'local' ];


    public function __construct()
    {
        if( !in_array( env('APP_ENV'), $this->devEnvironments ) )
        {
            abort( 404 );
        }
    }


    private function loginUser( User $user )
    {
        if( \Illuminate\Support\Facades\Auth::user()  &&  $user->id != \Illuminate\Support\Facades\Auth::user()->id )
        {
            \Illuminate\Support\Facades\Auth::logout();
        }

        if( null == \Illuminate\Support\Facades\Auth::user() )
        {            
            \Illuminate\Support\Facades\Auth::loginUsingId( $user->id, true );      // login and "remember" with true boolean
        }
    }


    private function loginDevUser()
    {
        $user = \DeveloperSeeder::getDeveloperUser();

        if( !$user )
        {
            abort( 400, 'Developer user not found. Did you run the develolper seeder?' );
        }

        $this->loginUser( $user );

        return $user;
    }


    public function dev()
    {
        $this->loginDevUser();
        return redirect()->route('admin.home');
    }


    public function devHome()
    {
        $this->loginDevUser();
        return redirect()->route('home');
    }


    public function devProducerFirst()
    {
        $this->loginDevUser();

        if( $organization = \App\Organization::orderBy('name')->first() )
        {
            return redirect()->route('producer.home', $organization->id);
        }

        return redirect()->route('home');
    }


    public function super()
    {
        $user = User::where('email', 'super@super.super')->first();

        if( !$user )
        {
            abort( 400, 'Super user not found. Did you run the develolper seeder?' );
        }

        $this->loginUser( $user );

        return redirect()->route('admin.home');
    }


    public function admin()
    {
        $user = User::where('email', 'admin@admin.admin')->first();

        if( !$user )
        {
            abort( 400, 'Admin user not found. Did you run the develolper seeder?' );
        }

        $this->loginUser( $user );

        return redirect()->route('admin.home');
    }


    public function user()
    {
        $user = User::where('email', 'user@user.user')->first();

        if( !$user )
        {
            abort( 400, 'User not found. Did you run the develolper seeder?' );
        }

        $this->loginUser( $user );

        return redirect()->route('home');
    }
}
