<?php

namespace App\Http\Controllers\L2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Rules\MoneyFormat;
use App\Rules\MoneyMax;
use App\Competition;
use App\CompetitionInstance;
use App\Event;
use App\Group;
use App\Organization;
use App\Rodeo;

class BuildSeriesCompetitionController extends Controller
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
     * Create competitions from events/groups of existing rodeo.
     */
    public function copyEvents( Request $request, Organization $organization, Rodeo $rodeo )
    {
        if( $organization->id != $rodeo->organization_id )
        {
            abort(403);
        }    

        if( $rodeo->competitions()->count() > 0 )    
        {
            return view('common_error_page')->with('message', 'Cannot copy events if a rodeo has already has events.');
        }

        if( !$rodeo->starts_at  ||  !$rodeo->ends_at  )
        {
            return view('common_error_page')->with('message', 'Cannot copy events if a rodeo does not have a start and end date.');            
        }


        $rodeoToCopy = $request->input('rodeo') ? Rodeo::find( $request->input('rodeo') ) : null;

        if( !$rodeoToCopy )
        {
            abort( 404 );
        }

        if( $organization->id != $rodeoToCopy->organization_id )
        {
            abort(403);
        }    

        if( $rodeoToCopy->competitions->count() < 1 )
        {
            return view('common_error_page')->with('message', 'Rodeo does not have any events to copy...');
        }

        // copy competition details and create instance for each rodeo day
        $startDate = $rodeo->starts_at->copy()->startOfDay();
        $endDate = $rodeo->ends_at->copy()->startOfDay();

        foreach( $rodeoToCopy->competitions as $competitionToCopy )
        {
            $competition = Competition::create([
                'organization_id' => $organization->id, 
                'rodeo_id' => $rodeo->id, 
                'event_id' => $competitionToCopy->event_id,
                'group_id' => $competitionToCopy->group_id,
                'entry_fee' => $competitionToCopy->entry_fee,
                'allow_multiple_entries_per_contestant' => $competitionToCopy->allow_multiple_entries_per_contestant,
                'max_entries_per_contestant' => $competitionToCopy->max_entries_per_contestant
            ]);

            $day = $startDate->copy();

            while ( $day <= $endDate ) 
            {
                CompetitionInstance::create([
                    'competition_id' => $competition->id, 
                    'starts_at' => $day
                ]);

                $day->addDays(1);
            }
        }

        $name = $rodeoToCopy->name ? $rodeoToCopy->name : "rodeo #{$rodeoToCopy->id}";
        return redirect()
                ->route('L2.build.series.rodeos.show', [$organization, $rodeo->series, $rodeo])
                ->with('successAlert', "Events copied from rodeo {$name}.");       
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Organization $organization )
    {
        abort( 404 );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( Request $request, Organization $organization, Rodeo $rodeo, Event $event, Group $group )
    {
        if( $organization->id != $rodeo->organization_id )
        {
            abort(403);
        }    

        if( $organization->id != $event->organization_id )
        {
            abort(403);
        }   

        if( $organization->id != $group->organization_id )
        {
            abort(403);
        }           

        $data['organization'] = $organization;
        $data['series'] = $rodeo->series;
        $data['rodeo'] = $rodeo;
        $data['event'] = $event;
        $data['group'] = $group;

        return view('L2.build_series.competition_create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request, Organization $organization, Rodeo $rodeo, Event $event, Group $group )
    {
        if( $organization->id != $rodeo->organization_id )      
        {
            abort(403);
        }    

        if( $organization->id != $event->organization_id )
        {
            abort(403);
        }   

        if( $organization->id != $group->organization_id )
        {
            abort(403);
        }  

        $count = Competition::where('rodeo_id', $organization->id)
                                ->where('event_id', $event->id)
                                ->where('group_id', $group->id)
                                ->count();
        if( $count > 0 )
        {
           // abort( 400, "There is aready a competition in the rodeo for the group and event...");
        }

        $validated = $request->validate([

            'entry_fee' => [
                'nullable', 
                new MoneyFormat(), 
                new MoneyMax(9999999999.99, '$', 'prepend')
            ],

            'allow_multiple_entries' => [
                'nullable'
            ],

            'max_entries_per_contestant' => [
                'nullable', 
                'numeric', 
                'integer',
            ],

            'days' => [
                'required',
                'array',
            ],

            'days.*' => [
                function ($attribute, $value, $fail) use ($rodeo) {
                    if ( $rodeo->starts_at  &&  $value < $rodeo->starts_at->copy()->startOfDay()->timestamp ) 
                    {
                        $fail('The selected days cannot be before the start of the rodeo.');
                    }
                    if ( $rodeo->ends_at  &&  $value > $rodeo->ends_at->copy()->startOfDay()->timestamp ) 
                    {
                        $fail('The selected days cannot be after the end of the rodeo.');
                    }
                },
            ]

        ]);

        if( Competition::where('rodeo_id', $rodeo->id)->where('event_id', $event->id)->where('group_id', $group->id)->count() > 0 )
        {
            return view('common_error_page')
                    ->with('message', "The {$group->name} - {$event->name} event already exists for the rodeo.");
        }

        $maxPerContestant = !isset($validated['allow_multiple_entries'])
                                ? 1
                                : (isset($validated['max_entries_per_contestant']) ? $validated['max_entries_per_contestant'] : null);

        $competition = Competition::create([
            'organization_id' => $organization->id, 
            'rodeo_id' => $rodeo->id, 
            'event_id' => $event->id,
            'group_id' => $group->id,
            'entry_fee' => $validated['entry_fee'],
            'max_entries_per_contestant' => $maxPerContestant,
        ]);

        foreach ($validated['days'] as $timestamp) 
        {
            CompetitionInstance::create([
                'competition_id' => $competition->id,
                'starts_at' => \Carbon\Carbon::createFromTimestamp( $timestamp )
            ]);
        }

        return redirect()
                ->route('L2.build.series.rodeos.show', [$organization, $rodeo->series, $rodeo])
                ->with('successAlert', "\"{$group->name} - {$event->name}\" added to rodeo.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Competition  $competition
     * @return \Illuminate\Http\Response
     */
    public function show( Organization $organization, Competition $competition )
    {
        abort( 404 );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Competition  $competition
     * @return \Illuminate\Http\Response
     */
    public function edit( Organization $organization, Competition $competition )
    {
        if( $organization->id != $competition->organization_id )
        {
            abort(403);
        }    

        $competition->load([ 'rodeo', 'rodeo.series', 'event', 'group', 'instances' ]);

        $data['organization'] = $organization;
        $data['series'] = $competition->rodeo->series;
        $data['rodeo'] = $competition->rodeo;
        $data['event'] = $competition->event;
        $data['group'] = $competition->group;

        $data['competition'] = $competition;

        return view('L2.build_series.competition_edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Competition  $competition
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, Organization $organization, Competition $competition )
    {
        if( $organization->id != $competition->organization_id )
        {
            abort(403);
        }   

        $competition->load(['rodeo', 'rodeo.series', 'event', 'group']);

        $rodeo = $competition->rodeo;

        $validated = $request->validate([

            'entry_fee' => [
                'nullable', 
                new MoneyFormat(), 
                new MoneyMax(9999999999.99, '$', 'prepend')
            ],
            'allow_multiple_entries' => [
                'nullable'
            ],
            'max_entries_per_contestant' => [
                'nullable', 
                'numeric', 
                'integer',
            ],
            'days' => [
                'required',
                'array',
            ],
            'days.*' => [
                function ($attribute, $value, $fail) use ($rodeo) {
                    if ( $rodeo->starts_at  &&  $value < $rodeo->starts_at->copy()->startOfDay()->timestamp ) 
                    {
                        $fail('The selected days cannot be before the start of the rodeo.');
                    }
                    if ( $rodeo->ends_at  &&  $value > $rodeo->ends_at->copy()->startOfDay()->timestamp ) 
                    {
                        $fail('The selected days cannot be after the end of the rodeo.');
                    }
                },
            ]
            
        ]);

        $maxPerContestant = !isset($validated['allow_multiple_entries'])
                                ? 1
                                : (isset($validated['max_entries_per_contestant']) ? $validated['max_entries_per_contestant'] : null);

        $competition->update([
            'entry_fee' => $validated['entry_fee'],
            'max_entries_per_contestant' => $maxPerContestant,
        ]);

      
        $updated = [];

        foreach( $competition->instances as $instance )
        {
            if( !$instance->starts_at )
            {
                $instance->delete();
            }

            if( $instance->starts_at )
            {
                $timestamp = $instance->starts_at->timestamp;

                if( in_array($timestamp, $validated['days']) )
                {
                    // nothing to update... (in future could possibly update location, etc.)
                    $updated[] = $timestamp;
                }
                else
                {
                    $instance->delete();
                }
            }
        }

        $daysToAdd = array_diff($validated['days'], $updated);

        foreach ($daysToAdd as $timestamp) 
        {
            CompetitionInstance::create([
                'competition_id' => $competition->id,
                'starts_at' => \Carbon\Carbon::createFromTimestamp( $timestamp )
            ]);
        }

        return redirect()
                ->route('L2.build.series.rodeos.show', [$organization, $rodeo->series, $rodeo])
                ->with('successAlert', "\"{$competition->group->name} - {$competition->event->name}\" updated.");        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Competition  $competition
     * @return \Illuminate\Http\Response
     */
    public function destroy( Organization $organization, Competition $competition )
    {
        if( $organization->id != $competition->organization_id )
        {
            abort(403);
        }

        $rodeo = $competition->rodeo;
        $series = $competition->rodeo->series;
        $group = $competition->group;
        $event = $competition->event;

        $competition->delete();

        return redirect()
                ->route('L2.build.series.rodeos.show', [$organization, $series, $rodeo])
                ->with('successAlert', "\"{$group->name} - {$event->name}\" removed from rodeo.");                
    }
}
