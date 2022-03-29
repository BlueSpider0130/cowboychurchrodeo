<?php

namespace App\Http\Controllers\L3;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Organization;
use App\Rodeo;
use App\Group;

class NotCheckedInController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('level-3');
    }  


    public function notCheckedIn( Organization $organization, Rodeo $rodeo )
    {
        if( $rodeo->organization_id != $organization->id )
        {
            abort(404);
        }

        
        $contestantIds = $rodeo->entries()->whereNull('checked_in_at')->pluck('contestant_id')->toArray();
        $competitionEntries = $rodeo->competition_entries()->whereIn('contestant_id', $contestantIds)->get();
        $competitionIds = $competitionEntries->pluck('competition_id')->toArray();
        $groupIds = \App\Competition::whereIn('id', $competitionIds)->pluck('group_id')->toArray();

        $groups = \App\Group::whereIn('id', $groupIds)->get()->sortBy('name', SORT_NATURAL, true);

        return view('L3.not_checked_in.not_checked_in_home')
                ->with('organization', $organization)
                ->with('rodeo', $rodeo)
                ->with('groups', $groups);
    }


    public function notCheckedInGroup( Organization $organization, Rodeo $rodeo, Group $group )
    {
        if( $rodeo->organization_id != $organization->id )
        {
            abort(404);
        }

        $competitions = $rodeo->competitions()->where('group_id', $group->id)->get();

        $entries = $rodeo
                    ->competition_entries()
                    ->whereIn('competition_id', $competitions->pluck('id')->toArray())
                    ->with('contestant', 'instance')
                    ->get()
                    ->sortBy('contestant.first_name');

        $byDay = [];

        foreach($entries as $entry)
        {
            $ts = $entry->instance->starts_at->timestamp;

            if( !array_key_exists($ts, $byDay) )
            {
                $byDay[$ts] = [];
            }

            $byDay[$ts][] = $entry;
        }

        ksort($byDay);
        


        $entries = [];

        foreach( $byDay as $dayEntries )
        {
            foreach( $dayEntries as $entry )
            {
                $entries[] = $entry;
            }
        }

        return view('L3.not_checked_in.not_checked_in_index')
                ->with('organization', $organization)
                ->with('rodeo', $rodeo)
                ->with('group', $group)
                ->with('entries', $entries);
    }

}
