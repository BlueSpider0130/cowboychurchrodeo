<?php

namespace App\Http\Controllers\L2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Organization;
use App\Rodeo;
use App\Contestant;
use App\Competition;
use App\CompetitionEntry;


class RegistrationEntryController extends Controller
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
    public function index( Organization $organization, Rodeo $rodeo, Contestant $contestant, Request $request )
    {
        if( $organization->id != $rodeo->organization_id )
        {
            abort( 404 );
        }

        if( $organization->id != $contestant->organization_id )
        {
            abort( 404 );
        }

        $competitions = $rodeo
                            ->competitions()
                            ->with('event', 'group', 'instances')
                            ->get();

        $groups = $competitions
                    ->pluck('group')
                    ->unique()
                    ->sortBy('name', SORT_NATURAL);
    
        $rodeoEntry = $rodeo
                        ->entries()
                        ->where('rodeo_id', $rodeo->id)
                        ->where('contestant_id', $contestant->id)
                        ->first();

        $competitionEntries = $rodeo
                                ->competition_entries()
                                ->with(['competition', 'competition.event', 'competition.group'])
                                ->where('contestant_id', $contestant->id)
                                ->get();                            

        $sortedCompetitions = [];

        foreach( $groups as $group )
        {
            $set = new \stdClass;
            $set->group = $group;
            $set->competitions = $competitions->where('group_id', $group->id)->sortBy('event.name', SORT_NATURAL); 

            $sortedCompetitions[] = $set;
        }

        $sortedCompetitions = collect( $sortedCompetitions );

        if( $request->ajax() || $request->wantsJson() )
        {
            $data= [
                'competitions' => $sortedCompetitions,
                'entries' => $competitionEntries,
            ];

            return response( $data, 200 );
        }

        return view('L2.registration.entries_index')
                ->with('organization', $organization)
                ->with('rodeo', $rodeo)
                ->with('contestant', $contestant)
                ->with('sortedCompetitions', $sortedCompetitions)
                ->with('rodeoEntry', $rodeoEntry)
                ->with('competitionEntries', $competitionEntries);        
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( Organization $organization, Rodeo $rodeo, Contestant $contestant, Competition $competition, Request $request )
    {
        if( $organization->id != $rodeo->organization_id )
        {
            abort( 404 );
        }

        if( $organization->id != $contestant->organization_id )
        {
            abort( 404 );
        }

        if( $organization->id != $competition->organization_id )
        {
            abort( 404 );
        }

        return view('L2.registration.entries_create')
                ->with('organization', $organization)
                ->with('rodeo', $rodeo)
                ->with('contestant', $contestant)
                ->with('competition', $competition);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Organization $organization, Rodeo $rodeo, Contestant $contestant, Competition $competition, Request $request )
    {
        if( $organization->id != $rodeo->organization_id )
        {
            abort( 404 );
        }

        if( $organization->id != $contestant->organization_id )
        {
            abort( 404 );
        }

        if( $organization->id != $competition->organization_id )
        {
            abort( 404 );
        }

        $competition->load(['event', 'instances']);

        $validated = $request->validate([
            'instance' => [
                'required', 
                Rule::in( $competition->instances->pluck('id')->toArray() ),                          
            ], 
            'position' => [
                'nullable', 
                Rule::requiredIf( $competition->event->is_team_roping ),
            ],             
            'requested_teammate' => [
                'nullable'
            ]
        ]);

        CompetitionEntry::create([
            'contestant_id' => $contestant->id, 
            'competition_id' => $competition->id,
            'instance_id' => $validated['instance'],         
            'position' => $competition->event->is_team_roping && isset($validated['position']) ? $validated['position'] : null, 
            'requested_teammate' => isset($validated['requested_teammate']) ? $validated['requested_teammate'] : null, 
        ]);

        $competitionName = $competition->group ? "{$competition->group->name} - {$competition->event->name}" : $competition->event->name;

        return redirect()
                ->route('L2.registration.entries.index', [$organization, $competition->rodeo_id, $contestant])
                ->with('successAlert', "{$contestant->name} registered for {$competitionName}");
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( Organization $organization, CompetitionEntry $entry, Request $request )
    {
        $entry->load('competition', 'competition.rodeo', 'contestant');

        if( $organization->id != $entry->competition->organization_id )
        {
            abort( 404 );
        }

        return view('L2.registration.entries_edit')
                ->with('organization', $organization)
                ->with('rodeo', $entry->competition->rodeo)
                ->with('contestant', $entry->contestant)
                ->with('competition', $entry->competition)
                ->with('entry', $entry);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update( Organization $organization, CompetitionEntry $entry, Request $request )
    {
        if( $organization->id != $entry->competition->organization_id )
        {
            abort( 404 );
        }

        $competition = $entry->competition;

        $validated = $request->validate([
            'instance' => [
                'required', 
                Rule::in( $competition->instances->pluck('id')->toArray() ),                          
            ], 
            'position' => [
                'nullable', 
                Rule::requiredIf( $competition->event->is_team_roping ),
            ],             
            'requested_teammate' => [
                'nullable'
            ]
        ]);

        $entry->update($validated);

        return redirect()
                ->route('L2.registration.entries.index', [$organization, $competition->rodeo_id, $entry->contestant_id])
                ->with('successAlert', "Registration entry updated.");        
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Organization $organization, CompetitionEntry $entry, Request $request )
    {
        $entry->load(['competition', 'competition.rodeo']);

        if( $organization->id != $entry->competition->organization_id )
        {
            abort( 404 );
        }

        $entry->delete();

        return redirect()
                ->route('L2.registration.entries.index', [$organization->id, $entry->competition->rodeo_id, $entry->contestant_id])
                ->with('successAlert', 'Registration entry deleted.');
    }
}
