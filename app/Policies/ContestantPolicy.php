<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Contestant;
use App\User;


class ContestantPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if( $user->isSuper() ) 
        {
            return true;
        }
    }

    /**
     * Determine whether the user can view any contestants.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the contestant.
     *
     * @param  \App\Models\User  $user
     * @param  \App\App\Models\Contestant  $contestant
     * @return mixed
     */
    public function view(User $user, Contestant $contestant)
    {
        return $user->isAdmin()  ||  $user->contestants()->wherePivot('contestant_id', $contestant->id)->count() > 0;
    }

    /**
     * Determine whether the user can create contestants.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create( User $user )
    {
        return true;
    }

    /**
     * Determine whether the user can update the contestant.
     *
     * @param  \App\Models\User  $user
     * @param  \App\App\Models\Contestant  $contestant
     * @return mixed
     */
    public function update( User $user, Contestant $contestant )
    {
        return $user->isAdmin()  ||  $user->contestants()->wherePivot('contestant_id', $contestant->id)->count() > 0;
    }

    /**
     * Determine whether the user can delete the contestant.
     *
     * @param  \App\Models\User  $user
     * @param  \App\App\Models\Contestant  $contestant
     * @return mixed
     */
    public function delete( User $user, Contestant $contestant )
    {
        return $user->isAdmin()  ||  $user->contestants()->wherePivot('contestant_id', $contestant->id)->count() > 0;
    }

    /**
     * Determine whether the user can restore the contestant.
     *
     * @param  \App\Models\User  $user
     * @param  \App\App\Models\Contestant  $contestant
     * @return mixed
     */
    public function restore( User $user, Contestant $contestant )
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the contestant.
     *
     * @param  \App\Models\User  $user
     * @param  \App\App\Models\Contestant  $contestant
     * @return mixed
     */
    public function forceDelete( User $user, Contestant $contestant )
    {
        return false;
    }
}
