<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Contestant' => 'App\Policies\ContestantPolicy',
        'App\Models\Document'   => 'App\Policies\DocumentPolicy',
        'App\Models\Event'      => 'App\Policies\EventPolicy',
        'App\Models\Group'      => 'App\Policies\GroupPolicy',
        'App\Models\Series'     => 'App\Policies\SeriesPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();


        Gate::define('access-level-2-for-organization', function ($user, \App\Organization $organization) {

            if( $user->isSuper()  ||  $user->isAdmin() )
            {
                return true;
            }

            return $user->hasLevelForOrganizationId( 2, $organization->id );
        
        });


        Gate::define('access-level-2-for-record', function($user, \Illuminate\Database\Eloquent\Model $record) {

            if( $user->isSuper()  ||  $user->isAdmin() )
            {
                return true;
            }

            return  null !== $record->organization_id  &&   $user->hasLevelForOrganizationId( 2, $record->organization_id );
        });


        Gate::define('access-level-3-for-organization', function ($user, \App\Organization $organization) {

            if( $user->isSuper()  ||  $user->isAdmin() )
            {
                return true;
            }

            if( Gate::allows('access-level-2-for-organization', $organization) )
            {
                 return true;
            }

            return $user->hasLevelForOrganizationId( 3, $organization->id );        
            
        });

    }
}
