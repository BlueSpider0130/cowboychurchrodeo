<?php

namespace App\Http\Controllers\L2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Competition;
use App\CompetitionEntry;
use App\Organization;
use App\TeamRopingEntry;

class TeamRopingEntryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('level-2');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Organization $organization )
    {
        abort( 400, 'No index...' );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( Organization $organization, Competition $competition )
    {
        if( $organization->id != $competition->organization_id )
        {
            abort( 403 );
        }

        $competition->load(['event', 'group']);

        $entryIds = $competition->entries()->pluck('id')->toArray();
        $assignedHeaderEntryIds = TeamRopingEntry::whereIn('header_entry_id', $entryIds)->pluck('header_entry_id')->toArray();
        $assignedHeelerEntryIds = TeamRopingEntry::whereIn('heeler_entry_id', $entryIds)->pluck('heeler_entry_id')->toArray();

        $headerEntries = $competition
                            ->entries()
                            ->where( function($q) {
                                return $q
                                        ->where('position', 'header')
                                        ->orWhereNull('position');                           
                            })
                            ->whereNotIn('id', $assignedHeaderEntryIds)
                            ->with(['contestant', 'instance'])
                            ->get()
                            ->sortBy('contestant.last_name');

        $heelerEntries = $competition
                            ->entries()
                            ->where( function($q) {
                                return $q
                                        ->where('position', 'heeler')
                                        ->orWhereNull('position');                           
                            })
                            ->whereNotIn('id', $assignedHeelerEntryIds)
                            ->with(['contestant', 'instance'])
                            ->get()
                            ->sortBy('contestant.last_name');

        $data['organization'] = $organization;
        $data['rodeo'] = $competition->rodeo;
        $data['competition'] = $competition;
        $data['headerEntries'] = $headerEntries;
        $data['heelerEntries'] = $heelerEntries;

        return view('L2.team_roping_entry_create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request, Organization $organization, Competition $competition )
    {
        if( $organization->id != $competition->organization_id )
        {
            abort( 403 );
        }

        $headerEntry = $request->input('header') ? CompetitionEntry::find( $request->input('header') ) : null;
        $heelerEntry = $request->input('heeler') ? CompetitionEntry::find( $request->input('heeler') ) : null;

        $validated = $request->validate([
            'header' => [
                'required',
                function ($attribute, $value, $fail) use ( $competition, $headerEntry, $heelerEntry ) {

                    if( $value )
                    {
                        if( !$headerEntry )
                        {
                            $fail( 'Contestant entry not found.' );

                            return null;
                        }

                        if( $headerEntry->competition_id != $competition->id )
                        {
                            $fail( 'Contestant entry is invalid.' );

                            return null;
                        }

                        if( $headerEntry->position  &&  $headerEntry->position != 'header' )
                        {
                            $fail( 'Can not assign contestant as header. Contestant was entered as '.$headerEntry->position.'.' );

                            return null;
                        }

                        if( TeamRopingEntry::where('header_entry_id', $value)->orWhere('heeler_entry_id', $value)->count() > 0 )
                        {
                            $fail( 'Contestant entry has already been assigned to another team.' );

                            return null;
                        }

                        if( $headerEntry  &&  $heelerEntry  &&  $headerEntry->contestant_id == $heelerEntry->contestant_id )
                        {
                            $fail( 'Header and heeler cannot be the same contestant.' );

                            return null;
                        }
                    }

                },

            ],

            'heeler' => [
                'required',
                function ($attribute, $value, $fail) use ( $competition, $heelerEntry, $headerEntry ) {
                    if( $value )
                    {
                        if( !$heelerEntry )
                        {
                            $fail( 'Contestant entry not found.' );

                            return null;
                        }

                        if( $heelerEntry->competition_id != $competition->id )
                        {
                            $fail( 'Contestant entry is invalid.' );

                            return null;
                        }

                        if( $heelerEntry->position  &&  $heelerEntry->position != 'heeler' )
                        {
                            $fail( 'Can not assign contestant as heeler. Contestant was entered as '.$heelerEntry->position.'.' );

                            return null;
                        }

                        if( TeamRopingEntry::where('heeler_entry_id', $value)->orWhere('heeler_entry_id', $value)->count() > 0 )
                        {
                            $fail( 'Contestant entry has already been assigned to another team.' );

                            return null;
                        }

                        if( $headerEntry  &&  $heelerEntry  &&  $headerEntry->contestant_id == $heelerEntry->contestant_id )
                        {
                            $fail( 'Header and heeler cannot be the same contestant.' );

                            return null;
                        }                        
                    }
                },                
            ], 

            'instance' => [
                'required', 
                function ($attribute, $value, $fail) use ( $competition, $heelerEntry, $headerEntry ) {
                    if( $headerEntry  &&  $heelerEntry )
                    {
                        // $headerInstanceIds = [$headerEntry->instances_id];
                        // $heelerInstanceIds = [$heelerEntry->instance_id];

                        // if( $headerInstanceIds  &&  !in_array($value, $headerInstanceIds) )
                        // {
                        //     $fail( 'The header entry is not for the selected day.' );
                        // }

                        // if( $heelerInstanceIds  &&  !in_array($value, $heelerInstanceIds) )
                        // {
                        //     $fail( 'The heeler entry is not for the selected day.' );
                        // }
                    }
                }               
            ]
        ]);

        $teamEntry = TeamRopingEntry::create([
            'header_entry_id' => $validated['header'],
            'heeler_entry_id' => $validated['heeler']
        ]);


        return redirect()
                ->route('L2.entries.index', [$organization, $competition])
                ->with('successAlert', 'Contestants assigned to team.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TeamRopingEntry  $teamRopingEntry
     * @return \Illuminate\Http\Response
     */
    public function show( Organization $organization, TeamRopingEntry $teamRopingEntry )
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TeamRopingEntry  $teamRopingEntry
     * @return \Illuminate\Http\Response
     */
    public function edit( Organization $organization, TeamRopingEntry $teamRopingEntry )
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TeamRopingEntry  $teamRopingEntry
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, Organization $organization, TeamRopingEntry $teamRopingEntry )
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TeamRopingEntry  $teamRopingEntry
     * @return \Illuminate\Http\Response
     */
    public function destroy( Organization $organization, TeamRopingEntry $entry )
    {
        $teamRopingEntry = $entry;

        if ($teamRopingEntry->header_entry)
        {
            $competition = $teamRopingEntry->header_entry->competition;

            if( $organization->id != $competition->organization_id )
            {
                abort( 403 );
            }

            if( $organization->id != $competition->organization_id )
            {
                abort( 403 );
            }
        }
    
        $teamRopingEntry->delete();
if ($teamRopingEntry->header_entry)
{
    return redirect()->route('L2.organizations.show', $organization);
}
        return redirect()
                ->route('L2.entries.index', [$organization, $competition])
                ->with('successAlert', 'Team assignment removed.');        
    }
}
