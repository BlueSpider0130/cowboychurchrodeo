<?php

namespace App\Http\Controllers\L4;

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
    }


    /**
     * Display entries.
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

        if( $request->user()->contestants()->where('contestant_id', $contestant->id)->count() < 1 )
        {
            abort( 403 );
        }

        if( $rodeo->isRegistrationClosed() )
        {
            return view('common_error_page')->with('message', 'Registration is closed for this rodeo.');
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

        return view('L4.registration.entries_index')
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

        if( $request->user()->contestants()->where('contestant_id', $contestant->id)->count() < 1 )
        {
            abort( 403 );
        }

        if( $organization->id != $competition->organization_id )
        {
            abort( 404 );
        }

        if( $rodeo->isRegistrationClosed() )
        {
            return view('common_error_page')->with('message', 'Registration is closed for this rodeo.');
        }

        return view('L4.registration.entries_create')
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

        if( $request->user()->contestants()->where('contestant_id', $contestant->id)->count() < 1 )
        {
            abort( 403 );
        }

        if( $organization->id != $competition->organization_id )
        {
            abort( 404 );
        }
        
        if( $rodeo->isRegistrationClosed() )
        {
            abort( 400, 'Registration is closed for this rodeo.' );
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
                ->route('L4.registration.entries.index', [$organization, $competition->rodeo_id, $contestant])
                ->with('successAlert', "{$contestant->name} registered for {$competitionName}");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CompetitionEntry  $competitionEntry
     * @return \Illuminate\Http\Response
     */
    public function edit( Organization $organization, CompetitionEntry $entry, Request $request )
    {
        $entry->load('competition', 'competition.rodeo', 'contestant');

        if( $organization->id != $entry->competition->organization_id )
        {
            abort( 404 );
        }

        if( $request->user()->contestants()->where('contestant_id', $entry->contestant_id)->count() < 1 )
        {
            abort( 403 );
        }

        if( $entry->competition->rodeo->isRegistrationClosed() )
        {
            return view('common_error_page')->with('message', 'Registration is closed for this rodeo.');
        }

        return view('L4.registration.entries_edit')
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
     * @param  \App\CompetitionEntry  $competitionEntry
     * @return \Illuminate\Http\Response
     */
    public function update( Organization $organization, CompetitionEntry $entry, Request $request )
    {
        if( $organization->id != $entry->competition->organization_id )
        {
            abort( 404 );
        }

        if( $request->user()->contestants()->where('contestant_id', $entry->contestant_id)->count() < 1 )
        {
            abort( 403 );
        }

        if( $entry->competition->rodeo->isRegistrationClosed() )
        {
            abort( 400, 'Registration is closed for this rodeo.' );
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
                ->route('L4.registration.entries.index', [$organization, $competition->rodeo_id, $entry->contestant_id])
                ->with('successAlert', "Registration entry updated.");        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CompetitionEntry  $competitionEntry
     * @return \Illuminate\Http\Response
     */
    public function destroy( Organization $organization, CompetitionEntry $entry, Request $request )
    {
        $entry->load(['competition', 'competition.rodeo']);

        if( $organization->id != $entry->competition->organization_id )
        {
            abort( 404 );
        }

        if( $request->user()->contestants()->where('contestant_id', $entry->contestant_id)->count() < 1 )
        {
            abort( 403 );
        }

        if( $entry->competition->rodeo->isRegistrationClosed() )
        {
            abort( 400, 'Registration is closed for this rodeo.' );
        }

        $entry->delete();

        return redirect()
                ->route('L4.registration.entries.index', [$organization->id, $entry->competition->rodeo_id, $entry->contestant_id])
                ->with('successAlert', 'Registration entry deleted.');
    }

    public function payment( Organization $organization, Rodeo $rodeo, Contestant $contestant, Request $request )
    {
        $pay_data =json_decode($request->input('pay_data'));
        // dd(count($pay_data));
        $pay_amount = 0;
        $isOfficeFee = true;
        for ($i=0; $i <= count($pay_data)-1; $i++) { 
            $pay_amount = $pay_amount + $pay_data[$i] -> entry_fee;
            if ($pay_data[$i] -> group -> name != 'PEE WEE') {
                $isOfficeFee = false;
            }
        }
        if( $organization->id != $rodeo->organization_id )
        {
            abort( 500 );
        }

        if( $organization->id != $contestant->organization_id )
        {
            abort( 405 );
        }

        if( $request->user()->contestants()->where('contestant_id', $contestant->id)->count() < 1 )
        {
            abort( 403 );
        }

        if( $rodeo->isRegistrationClosed() )
        {
            return view('common_error_page')->with('message', 'Registration is closed for this rodeo.');
        }


        $competitions = $rodeo
                            ->competitions()
                            ->with('event', 'group', 'instances')
                            ->get();
        // dd($rodeo);

        $groups = $competitions
                    ->pluck('group')
                    ->unique()
                    ->sortBy('name', SORT_NATURAL);
    
        // $rodeoEntry = $rodeo
        //                 ->entries();
                        // ->where('rodeo_id', $rodeo->id)
                        // ->where('contestant_id', $contestant->id)
                        // ->first();
        // dd($rodeoEntry); exit();

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

        return view('L4.registration.add_card')
                ->with('organization', $organization)
                ->with('rodeo', $rodeo)
                ->with('contestant', $contestant)
                ->with('sortedCompetitions', $sortedCompetitions)
                ->with('payData', $pay_data)
                ->with('payer_user_name', $request->user()->name)
                ->with('payer_user_email', $request -> user()->email)
                ->with('payAmount', $pay_amount)
                ->with('competitionEntries', $competitionEntries)
                ->with('isOfficeFee', $isOfficeFee);        
    }

}
