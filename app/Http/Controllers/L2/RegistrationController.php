<?php

namespace App\Http\Controllers\L2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Organization;
use App\Contestant;
use App\Rodeo;
use App\RodeoEntry;
use App\CompetitionEntry;
use App\Exceptions\RegistrationException;


class RegistrationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('level-2')->except('show');
        $this->middleware('level-3')->only('show');
    }


    /**
     * List rodeos to register in
     */
    public function rodeoIndex( Organization $organization )
    {
        $rodeos = $organization
                    ->rodeos()
                    ->notEnded()
                    ->orderBy('starts_at')
                    ->get();

        $endedRodeos = $organization
                    ->rodeos()
                    ->ended()
                    ->orderBy('starts_at')
                    ->get();

        return view('L2.registration.rodeos_index')
                ->with('organization', $organization)
                ->with('rodeos', $rodeos)
                ->with('endedRodeos', $endedRodeos);
    }


    /**
     * List contestants to register 
     */
    public function contestantIndex( Request $request, Organization $organization, Rodeo $rodeo )
    {
        $query = $organization
                        ->contestants();

        if( $searchString = $request->input('search') )
        {
            $substrings = explode(' ', $searchString);

            $query->where(function($q) use ($substrings) {
                foreach($substrings as $string)
                {
                    $q->orWhere('first_name', 'like', $string.'%')
                        ->orWhere('last_name', 'like', $string.'%');
                }
                return $q;
            });
        }

        $contestants = $query
                        ->with(['rodeo_entries'])
                        ->orderBy('last_name')
                        ->get();

        return view('L2.registration.contestants_index')
                ->with('organization', $organization)
                ->with('rodeo', $rodeo)
                ->with('contestants', $contestants);
    }


    /**
     * Show contestant registration 
     */
    public function show( Request $request, Organization $organization, Rodeo $rodeo, Contestant $contestant )
    {
        if( $organization->id != $rodeo->organization_id )
        {
            abort( 404 );
        }

        if( $organization->id != $contestant->organization_id )
        {
            abort( 404 );
        }        

        $rodeo->load(['entries', 'competition_entries']);

        $rodeoEntry = $rodeo
                        ->entries()
                        ->where('contestant_id', $contestant->id)
                        ->first();

        $competitionEntries = $rodeo
                    ->competition_entries()
                    ->with(['competition', 'competition.event', 'competition.group'])
                    ->where('contestant_id', $contestant->id)
                    ->get()
                    ->sort( function($a, $b) {
                        if( $a->competition->group->name === $b->competition->group->name ) 
                        {
                            if( $a->competition->event->name === $b->competition->event->name ) 
                            {
                                return 0;
                            }

                            return strnatcmp($a->competition->event->name, $b->competition->event->name);
                        } 

                        return strnatcmp($a->competition->group->name, $b->competition->group->name);
                    });


        $data['organization']       = $organization;
        $data['rodeo']              = $rodeo;
        $data['contestant']         = $contestant;
        $data['rodeoEntry']         = $rodeoEntry;
        $data['competitionEntries'] = $competitionEntries;

        return view('L2.registration.show', $data);        
    }


    /**
     * Save contestant registration
     */
    public function save( Organization $organization, Rodeo $rodeo, Contestant $contestant, Request $request )
    {
        if( $organization->id != $rodeo->organization_id )
        {
            abort( 404 );
        }

        if( $organization->id != $contestant->organization_id )
        {
            abort( 404 );
        }

        $rodeoEntry = $rodeo
                        ->entries()
                        ->where('contestant_id', $contestant->id)
                        ->first();

        if( !$rodeoEntry )
        {
            RodeoEntry::create([
                'contestant_id' => $contestant->id,
                'rodeo_id' => $rodeo->id
            ]);

            return redirect()
                ->route('L2.registration.show', [$organization->id, $rodeo->id, $contestant->id])
                ->with('successAlert', 'Contestant registered for rodeo.');
        }
        else
        {
            $lastUpdatedAt = $rodeo
                            ->competition_entries()
                            ->where('contestant_id', $contestant->id)
                            ->get()
                            ->max('updated_at');

            if( $lastUpdatedAt > $rodeoEntry->updated_at )
            {
                $rodeoEntry->touch();

                return redirect()
                    ->route('L2.registration.show', [$organization->id, $rodeo->id, $contestant->id])
                    ->with('successAlert', 'Registration updated.');
            }
        }
        
        return redirect()
            ->route('L2.registration.show', [$organization->id, $rodeo->id, $contestant->id]);
    }


    /**
     * Delete contestant registration
     */
    public function destroy( Organization $organization, Rodeo $rodeo, Contestant $contestant, Request $request )
    {
        if( $organization->id != $rodeo->organization_id )
        {
            abort( 404 );
        }

        if( $organization->id != $contestant->organization_id )
        {
            abort( 404 );
        }

        $rodeo->competition_entries()->where('contestant_id', $contestant->id)->delete();
        $rodeoEntry = $rodeo->entries()->where('contestant_id', $contestant->id)->delete();

        
        return redirect()
            ->route('L2.registration.show', [$organization->id, $rodeo->id, $contestant->id])
            ->with('successAlert', 'Registration deleted.');
    }



}
