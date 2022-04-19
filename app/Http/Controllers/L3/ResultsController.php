<?php

namespace App\Http\Controllers\L3;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Organization;
use App\Rodeo;
use App\Competition;
use App\CompetitionEntry;
use App\Series;
use App\Membership;

class ResultsController extends Controller
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


    public function home( Organization $organization )
    {
        $active = $organization
                    ->rodeos()
                    ->current()
                    ->orderBy('starts_at')
                    ->get();
                    
        $scheduled = $organization
        ->rodeos()
        ->scheduled()
        ->orderBy('starts_at')
        ->get();

        $ended = $organization
                    ->rodeos()
                    ->ended()
                    // ->whereNotIn('id', $active->pluck('id')->toArray())
                    ->orderBy('starts_at', 'DESC')
                    ->get();


        return view('L3.results.home')
                ->with('organization', $organization)
                ->with('active', $active)
                ->with('ended', $ended)
                ->with('scheduled', $scheduled);
    }    


    public function index( Organization $organization, Rodeo $rodeo )
    {
        if( $organization->id != $rodeo->organization_id )
        {
            abort( 404 );
        }

        $competitions = $rodeo
                        ->competitions()
                        ->with(['event', 'group'])
                        ->get()
                        ->sort( function($a, $b) {
                            if( $a->group->name === $b->group->name ) 
                            {
                                if( $a->event->name === $b->event->name ) 
                                {
                                    return 0;
                                }

                                return strnatcmp($a->event->name, $b->event->name);
                            } 

                            return strnatcmp($a->group->name, $b->group->name);
                        });                        

        return view('L3.results.index')
                ->with('organization', $organization)
                ->with('rodeo', $rodeo)
                ->with('competitions', $competitions);

    }


    public function show( Organization $organization, Rodeo $rodeo, Competition $competition )
    {
        if( $organization->id != $rodeo->organization_id )
        {
            abort( 404 );
        }

        if( $rodeo->id != $competition->rodeo_id )
        {
            abort( 404 );
        } 

        $memberships = $rodeo
                        ->series()
                        ->with('memberships')
                        ->get()
                        ->pluck('memberships');
                        // dd($series); exit();
        $checkInIds = \App\RodeoEntry::where('rodeo_id', $competition->rodeo_id)
                            ->whereNotNull('checked_in_at')
                            ->pluck('contestant_id')
                            ->toArray();
        $entries = $rodeo
                    ->competition_entries()
                    ->where('competition_id', $competition->id)
                    ->with(['contestant'])
                    ->get()
                    ->sortBy('contestant.last_name');

        return view('L3.results.show')
                ->with('organization', $organization)
                ->with('rodeo', $rodeo)
                ->with('competition', $competition)
                ->with('entries', $entries)
                ->with('checkInIds', $checkInIds)
                ->with('memberships', $memberships);
    }


    public function edit( Organization $organization, Rodeo $rodeo, Competition $competition )
    {
        if( $organization->id != $rodeo->organization_id )
        {
            abort( 404 );
        }

        if( $rodeo->id != $competition->rodeo_id )
        {
            abort( 404 );
        } 

        $entries = $rodeo
                    ->competition_entries()
                    ->where('competition_id', $competition->id)
                    ->with(['contestant'])
                    ->get()
                    ->sortBy('contestant.last_name');

        return view('L3.results.edit')
                ->with('organization', $organization)
                ->with('rodeo', $rodeo)
                ->with('competition', $competition)
                ->with('entries', $entries);
    }


    public function update( Organization $organization, Rodeo $rodeo, Competition $competition, Request $request )
    {
        if( $organization->id != $rodeo->organization_id )
        {
            abort( 404 );
        }

        if( $rodeo->id != $competition->rodeo_id )
        {
            abort( 404 );
        } 

        $entryIds = $rodeo->competition_entries->pluck('id')->toArray();

        $validated = $request->validate([
            'entries' => [
                'required', 
                'array',
            ], 
            'entries.*' => [
                function ($attribute, $value, $fail) use ($entryIds, $competition) {
                    
                    $parts = explode('.', $attribute);
                    $id = isset($parts[1]) ? $parts[1] : null; 

                    if( !in_array($id, $entryIds) )
                    {
                        $fail("Entry $id is invalid for this event.");
                    }

                    // check value 
                    // $competition->event->result_type ... 
                },
            ]
        ]);

        \Illuminate\Support\Facades\DB::transaction( function() use ($validated) { 

            foreach ($validated['entries'] as $entryId => $score) 
            {
                CompetitionEntry::where('id', $entryId)->update([ 'score' => $score ]);
            }

        });

        return redirect()
                ->route('L3.results.show', [$organization->id, $rodeo->id, $competition->id])
                ->with('successAlert', 'Results updated.');
    }
}
