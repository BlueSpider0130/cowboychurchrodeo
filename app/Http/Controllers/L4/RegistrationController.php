<?php

namespace App\Http\Controllers\L4;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Organization;
use App\Rodeo;
use App\Contestant;
use App\RodeoEntry;


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
    }


    /**
     * List rodeos to register in
     */
    public function rodeoIndex( Organization $organization, Request $request )
    {
        $rodeos = $organization
                    ->rodeos()
                    ->whereNotNull('starts_at')
                    ->whereNotNull('ends_at')
                    ->notEnded()
                    ->orderBy('starts_at')
                    ->get();  

        return view('L4.registration.rodeo_index')
                    ->with('organization', $organization)
                    ->with('rodeos', $rodeos);
    }


    /**
     * List contestant to register (or redirect if user only has one contestant)
     */
    public function contestantIndex( Organization $organization, Rodeo $rodeo, Request $request )
    {
        if( $organization->id != $rodeo->organization_id )
        {
            abort( 404 );
        }

        $userContestantIds = $request->user()->contestants->pluck('id')->toArray();
        
        $contestants = $organization
                        ->contestants()
                        ->whereIn('id', $userContestantIds)
                        ->with(['rodeo_entries'])
                        ->orderBy('first_name')
                        ->get();

        if( 1 == $contestants->count() )
        {
            return redirect()
                    ->route('L4.registration.show', [$organization, $rodeo, $contestants->first()]);
        }

        return view('L4.registration.contestant_index')
                ->with('organization', $organization)
                ->with('rodeo', $rodeo)
                ->with('contestants', $contestants);
    }


    /**
     * Show contestant registration
     */
    public function show( Organization $organization, Rodeo $rodeo, Contestant $contestant, Request $request )
    {
        if( $organization->id != $rodeo->organization_id )
        {
            abort( 404 );
        }

        if( $organization->id != $contestant->organization_id )
        {
            abort( 404 );
        }

        if( $request->user()->contestants()->where('contestant_id', $contestant->id)->count() < 1 )
        {
            abort( 403 );
        }

        $rodeo->load(['entries', 'competition_entries']);
        $rodeoEntry = $rodeo
                        ->entries()
                        ->where('contestant_id', $contestant->id)
                        ->first(); //contestant_id & rodeo_id with organization and 
                        // var_dump(json_encode($rodeoEntry));

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

        return view('L4.registration.show', $data);
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

        if( $request->user()->contestants()->where('contestant_id', $contestant->id)->count() < 1 )
        {
            abort( 403 );
        }

        $rodeoEntry = $rodeo
                        ->entries()
                        ->where('contestant_id', $contestant->id)
                        ->with(['contestant', 'contestant.users'])
                        ->first();
        // dd($rodeoEntry); exit();

        if( $rodeoEntry )
        {
            $lastUpdatedAt = $rodeo
                            ->competition_entries()
                            ->where('contestant_id', $contestant->id)
                            ->get()
                            ->max('updated_at');
////////if user edite entries after pay for some enteries, then user should pay for only entries that is edited after first payment. So developer need to add feature of it. Here!!!
//////// update('checked_id_notes', 'not payed for only updated entries and payed for entries before editing!!!!!!')  Please use updated date!!!
            if( $lastUpdatedAt > $rodeoEntry->updated_at )
            {
                $rodeoEntry->touch();
            }
        }

        if( !$rodeoEntry )
        {
            $rodeoEntry = RodeoEntry::create([
                'contestant_id' => $contestant->id,
                'rodeo_id' => $rodeo->id,
                // 'check_in_notes' => '123456'
            ]);

            $rodeoEntry->touch();
        }

        return redirect()
            ->route('L4.registration.confirmation', [$organization->id, $rodeo->id, $contestant->id]);
    }


    /**
     * Show confirmation registration
     */
    public function confirmation( Organization $organization, Rodeo $rodeo, Contestant $contestant, Request $request )
    {
        if( $organization->id != $rodeo->organization_id )
        {
            abort( 404 );
        }

        if( $organization->id != $contestant->organization_id )
        {
            abort( 404 );
        }

        if( $request->user()->contestants()->where('contestant_id', $contestant->id)->count() < 1 )
        {
            abort( 403 );
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

        return view('L4.registration.confirmation', $data);
    }


    /**
     * Show entered
     */
    public function entered( Organization $organization, Request $request )
    {
        $contestantIds = $request
                            ->user()
                            ->contestants
                            ->pluck('id')
                            ->toArray();
        $rodeoIds = $organization
                        ->rodeos()
                        ->whereNotNull('starts_at')
                        ->whereNotNull('ends_at')
                        ->notEnded()
                        ->pluck('id')
                        ->toArray();
        
        $rodeoEntries = RodeoEntry::with([
                                'contestant',
                                'rodeo',
                            ])
                            ->whereIn('rodeo_id', $rodeoIds)
                            ->whereIn('contestant_id', $contestantIds)
                            ->whereNull('checked_in_at')
                            ->get()
                            ->sort( function($a, $b) {
                                if( $a->rodeo->id === $b->rodeo->id ) 
                                {
                                    if( $a->contestant->last_name === $b->contestant->last_name ) 
                                    {
                                        return 0;
                                    }

                                    return strnatcmp($a->contestant->last_name, $b->contestant->last_name);
                                } 

                                return $a->rodeo->starts_at > $b->rodeo->starts_at;
                            });

        return view('L4.registration.entered')
            ->with('organization', $organization)
            ->with('rodeoEntries', $rodeoEntries);    
    }

    // public function payment( Organization $organization, Request $request )
    // {

    // }

}
