<?php

namespace App\Http\Controllers\L4;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Organization;
use App\Rodeo;
use App\Competition;

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
    }


    public function home( Organization $organization, Request $request )
    {
        $rodeos = $organization
                    ->rodeos()
                    ->whereNotNull('starts_at')
                    ->whereNotNull('ends_at')
                    ->started()
                    ->orderBy('starts_at')
                    ->get();

        return view('L4.results.home')
                ->with('organization', $organization)
                ->with('rodeos', $rodeos);
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

        return view('L4.results.index')
                ->with('organization', $organization)
                ->with('rodeo', $rodeo)
                ->with('competitions', $competitions);

    }


    public function show( Organization $organization, Rodeo $rodeo, Competition $competition, Request $request )
    {
        if( $organization->id != $rodeo->organization_id )
        {
            abort( 404 );
        }

        if( $rodeo->id != $competition->rodeo_id )
        {
            abort( 404 );
        } 

        $direction = 'time' == $competition->event->result_type ? 'asc' : 'desc';

        $entries = $rodeo
                    ->competition_entries()
                    ->where('competition_id', $competition->id)
                    ->with(['contestant'])
                    ->orderBy('score', $direction)
                    ->get();
                    //->sortBy('contestant.last_name');

        $ownContestantIds = $request
                                ->user()
                                ->contestants
                                ->pluck('id')
                                ->toArray();

        return view('L4.results.show')
                ->with('organization', $organization)
                ->with('rodeo', $rodeo)
                ->with('competition', $competition)
                ->with('entries', $entries)
                ->with('ownContestantIds', $ownContestantIds);
    }    
}
