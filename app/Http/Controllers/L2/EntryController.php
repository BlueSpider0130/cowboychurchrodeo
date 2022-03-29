<?php

namespace App\Http\Controllers\L2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Contestant;
use App\Competition;
use App\CompetitionInstance;
use App\CompetitionEntry;
use App\Organization;
use App\Rodeo;
use App\RodeoEntry;

class EntryController extends Controller
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


    public function home( Organization $organization )
    {
        $data['organization'] = $organization;

        $data['rodeos'] = $organization
                            ->rodeos()
                            ->with(['entries'])
                            ->notEnded()
                            ->orderBy('starts_at')
                            ->get();

        $data['previous'] = $organization
                            ->rodeos()
                            ->with(['entries'])
                            ->ended()
                            ->orderBy('ends_at', 'desc')
                            ->get();

        return view('L2.entries.entries_home', $data);
    }


    public function rodeo( Organization $organization, Rodeo $rodeo )
    {
        if( $organization->id != $rodeo->organization_id )
        {
            abort( 403 );
        }

        $data['organization'] = $organization;
        $data['rodeo'] = $rodeo;

        $data['rodeoEntries'] = $rodeo
                                    ->entries()
                                    ->with(['contestant'])
                                    ->get()
                                    ->sortBy('contestant.last_name');

        // sort by group name, event name
        $data['competitions'] = $rodeo
                                    ->competitions()
                                    ->with([ 'entries', 'event', 'group' ])
                                    ->whereIn('event_id', $organization->events()->pluck('id')->toArray())          // why? fix!
                                    ->whereIn('group_id', $organization->groups()->pluck('id')->toArray())          // why? fix!
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

        return view('L2.entries.entries_rodeo', $data);        
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Organization $organization, Competition $competition )
    {
        if( $organization->id != $competition->organization_id )
        {
            abort( 403 );
        }

        $competition->load([ 'event', 'group', 'entries', 'entries.contestant', 'entries.instance' ]);
        $competition->entries = $competition->entries->sortBy('contestant.last_name');


        $data['organization'] = $organization;
        $data['rodeo'] = $competition->rodeo;
        $data['competition'] = $competition;
        $data['teamRopingEntries'] = \App\TeamRopingEntry::forCompetition($competition->id)->with(['header_entry', 'header_entry.contestant', 'heeler_entry', 'heeler_entry.contestant', 'instance'])->get();

        return view('L2.entries.entries_index', $data);
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

        // only get contestants that can be entered, 
        // i.e. check max number of entries for competition, 
        // and find contestants
        $query = $organization->contestants();

        $maxEntries = $competition->allow_multiple_entries_per_contestant  ?  $competition->max_entries_per_contestant  :  1;
        
        if( $maxEntries )
        {
            $entered = [];

            foreach( $competition->entries as $entry )
            {
                $entered[$entry->contestant_id] = array_key_exists($entry->contestant_id, $entered)
                                                    ? $entered[$entry->contestant_id] + 1
                                                    : 1;
            }

            $excluded = []; 

            foreach( $entered as $id => $count )
            {
                if( $count >= $maxEntries )
                {
                    $excluded[] = $id;
                }
            }

            if( $excluded )
            {
                $query->whereNotIn('id', $excluded);
            }
        }

        $contestantOptions = $query
                                ->orderBy('last_name')
                                ->get()
                                ->pluck('lexical_name_order', 'id')
                                ->toArray();

        $data['organization'] = $organization;
        $data['rodeo'] = $competition->rodeo;
        $data['competition'] = $competition;
        $data['contestantOptions'] = $contestantOptions;

        return view('L2.entries.entries_create', $data);
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

        $contestant = $request->input('contestant')  ?  Contestant::find( $request->input('contestant') )  :  null;
        $instance = $request->input('instance') ? CompetitionInstance::find( $request->input('instance') ) : null;

        $instanceIds = $competition->instances()->pluck('id')->toArray();

        $rules = [
            'contestant' => [
                'required', 
                function ($attribute, $value, $fail) use ($contestant, $competition) {

                    if( !$contestant )
                    {
                        $fail('Contestant not found.');
                    }

                    if( $contestant->organization_id != $competition->organization_id )
                    {
                        $fail('Contestant is not valid for the organization.');
                    }

                    $maxEntries = $competition->allow_multiple_entries_per_contestant  ?  $competition->max_entries_per_contestant  :  1;

                    if( $maxEntries  &&  $value )
                    {
                        $contestantEntryCount = CompetitionEntry::where('contestant_id', $value)->where('competition_id', $competition->id)->count();

                        if( $contestantEntryCount >= $maxEntries )
                        {
                            $fail('Contestant has already been entered into the event the maximum number of times.');
                        }
                    }
                },
            ], 
            'position' => [
                'nullable',
                Rule::requiredIf( $competition->event->team_roping ),
                Rule::in(['', '0', 'header', 'heeler'])
            ], 
            'no_fee' => [
                'nullable',
            ], 
            'no_score' => [
                'nullable',
            ], 
            'instance' => [
                'required', 
                Rule::in( $instanceIds ),
                function ($attribute, $value, $fail) use ($instance, $competition) {

                    if( !$instance )
                    {
                        $fail($attribute.' not found.');
                    }

                    if( $instance->competition_id != $competition->id )
                    {
                        $fail($attribute.' is not valid for this event.');
                    }
                },                            
            ]
        ];

        $validated = $request->validate($rules);

        CompetitionEntry::create([
            'competition_id' => $competition->id,
            'contestant_id'  => $contestant->id,
            'position'       => isset($validated['position']) ? $validated['position'] : null, 
            'no_fee'         => isset($validated['no_fee']) ? true : false, 
            'no_score'       => isset($validated['no_score']) ? true : false,
            'instance_id'    => $validated['instance']
        ]);

        return redirect()
                ->route('L2.entries.index', [$organization, $competition])
                ->with('successAlert', 'Contestant entered into event.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entry  $entry
     * @return \Illuminate\Http\Response
     */
    public function show( Organization $organization, CompetitionEntry $entry )
    {
        if( $organization->id != $entry->competition->organization_id )
        {
            abort( 403 );
        }

        $entry->load([ 'competition', 'competition.rodeo', 'competition.event', 'competition.group' ]);

        $data['organization'] = $organization;
        $data['rodeo'] = $entry->competition->rodeo;
        $data['competition'] = $entry->competition;
        $data['entry'] = $entry;

        return view('L2.entries.entries_show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entry  $entry
     * @return \Illuminate\Http\Response
     */
    public function edit( Organization $organization, CompetitionEntry $entry )
    {
        $competition = $entry->competition;

        if( $organization->id != $competition->organization_id )
        {
            abort( 403 );
        }
        
        $data['organization'] = $organization;
        $data['rodeo'] = $entry->competition->rodeo;
        $data['competition'] = $entry->competition;
        $data['entry'] = $entry;

        return view('L2.entries.entries_edit', $data);        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entry  $entry
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, Organization $organization, CompetitionEntry $entry )
    {
        $competition = $entry->competition;

        if( $organization->id != $competition->organization_id )
        {
            abort( 403 );
        }

        $instance = $request->input('instance') ? CompetitionInstance::find( $request->input('instance') ) : null;

        $instanceIds = $competition->instances()->pluck('id')->toArray();

        $rules = [
            'position' => [
                'nullable',
                Rule::requiredIf( $competition->event->team_roping ),
                Rule::in(['', '0', 'header', 'heeler'])
            ], 
            'no_fee' => [
                'nullable',
            ], 
            'no_score' => [
                'nullable',
            ], 
            'instance' => [
                'required', 
                Rule::in( $instanceIds ),
                function ($attribute, $value, $fail) use ($instance, $competition) {

                    if( !$instance )
                    {
                        $fail($attribute.' not found.');
                    }

                    if( $instance->competition_id != $competition->id )
                    {
                        $fail($attribute.' is not valid for this event.');
                    }
                },                            
            ]
        ];

        $validated = $request->validate($rules);

        $entry->update([
            'position' => isset($validated['position']) ? $validated['position'] : null, 
            'no_fee' => isset($validated['no_fee']) ? true : false, 
            'no_score' => isset($validated['no_score']) ? true : false, 
            'instance_id' => $validated['instance']
        ]);

        return redirect()
                ->route('L2.entries.index', [$organization, $competition])
                ->with('successAlert', 'Entry updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entry  $entry
     * @return \Illuminate\Http\Response
     */
    public function destroy( Organization $organization, CompetitionEntry $entry )
    {
        $competition = $entry->competition;

        if( $organization->id != $competition->organization_id )
        {
            abort( 403 );
        }

        $contestant = $entry->contestant;
  
        $entry->delete();

        return redirect()
                ->route('L2.entries.index', [$organization, $competition])
                ->with('successAlert', "{$contestant->name} removed from event.");
    }
}
