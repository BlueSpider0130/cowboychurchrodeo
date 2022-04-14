<?php

namespace App\Http\Controllers\L3;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Organization;
use App\Rodeo;
use App\Contestant;
use App\RodeoEntry;
use App\Membership;

class CheckInController extends Controller
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


    /**
     * List rodeos
     */
    public function home( Organization $organization )
    {
        $inProgress = $organization
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
                    ->orderBy('starts_at')
                    ->get();

        return view('L3.check-in.home')
                ->with('organization', $organization)
                ->with('inProgress', $inProgress)
                ->with('scheduled', $scheduled)
                ->with('ended', $ended);
    }


    /**
     * Show summary of check-in and buttons/links for check in, checked in, not checked in, etc.
     */
    public function rodeoSummary( Organization $organization, Rodeo $rodeo )
    {
        if( $rodeo->organization_id != $organization->id )
        {
            abort(404);
        }

        $rodeo->load('entries');

        return view('L3.check-in.rodeo')
                ->with('organization', $organization)
                ->with('rodeo', $rodeo)
                ->with('checkedInCount', $rodeo->entries->whereNotNull('checked_in_at')->count())
                ->with('notCheckedInCount', $rodeo->entries->whereNull('checked_in_at')->count())
                ->with('rodeoEntryCount', $rodeo->entries->count());
    }


    /**
     * Contestants already checked in for rodeo
     */
    public function checkedIn( Organization $organization, Rodeo $rodeo, Request $Request )
    {
        if( $rodeo->organization_id != $organization->id )
        {
            abort(404);
        }

        $checkedInEntries = $rodeo
                            ->entries()
                            ->whereNotNull('checked_in_at')
                            ->with(['contestant'])
                            ->get()
                            ->sortBy('contestant.last_name');

        return view('L3.check-in.checked_in')
                ->with('organization', $organization)
                ->with('rodeo', $rodeo)
                ->with('checkedInEntries', $checkedInEntries);                            
    }


    /**
     * Check in contestants for rodeo
     */
    public function rodeoContestantList( Organization $organization, Rodeo $rodeo, Request $request )
    {
        if( $rodeo->organization_id != $organization->id )
        {
            abort(404);
        }

        $checkInEntries = $rodeo
                            ->entries()
                            ->get();

        $checkedInEntries = $rodeo
                            ->entries()
                            ->whereNotNull('checked_in_at')
                            ->with(['contestant'])
                            ->get()
                            ->sortBy('contestant.last_name');

        $checkedInContestantIds = $checkedInEntries->pluck('contestant_id')->toArray();

        $entries = $rodeo
                    ->competition_entries()
                    ->whereNotIn('contestant_id', $checkedInContestantIds)
                    ->with(['instance', 'contestant', 'contestant.memberships'])
                    ->get()
                    ->sortBy('instance.starts_at');

        $contestantIds = [];
        $contestantsByDay = [];

        foreach( $entries as $entry )
        {
            if( !in_array($entry->contestant_id, $contestantIds) )
            {
                if( $entry->instance  &&  $day = $entry->instance->starts_at )
                {
                    $day = $day->copy()->startOfDay();
                    $timestamp = $day->timestamp;

                    if( !array_key_exists($timestamp, $contestantsByDay) )
                    {
                        $contestantsByDay[$timestamp] = [
                            'day' => $day,
                            'contestants' => []
                        ];
                    }

                    $contestantsByDay[$timestamp]['contestants'][] = $entry->contestant;

                    $contestantIds[] = $entry->contestant_id;
                }
            }            
        }

        foreach( $contestantsByDay as $timestamp => $data )
        {
            $contestants = collect($data['contestants'])
                            ->sortBy(function($contestant) {
                                    return [$contestant->last_name, $contestant->first_name];
                                });

            $contestantsByDay[$timestamp]['contestants'] = $contestants;
        }

        return view('L3.check-in.rodeo_contestant_list')
                ->with('organization', $organization)
                ->with('rodeo', $rodeo)
                ->with('contestantsByDay', $contestantsByDay)
                ->with('checkedInEntries', $checkedInEntries)
                ->with('checkInEntries', $checkInEntries);
    }


    public function summary( Organization $organization, Rodeo $rodeo, Request $request )
    {
        if( $rodeo->organization_id != $organization->id )
        {
            abort(404);
        }

        $request->validate([
            'contestants'   => [ 'required', 'array' ], 
            'contestants.*' => [ 'string' ],
            'memberships'   => [ 'nullable', 'array' ], 
            'memberships.*' => [ 'string' ]
        ]);

        $organizationContestants = $organization
                                    ->contestants()
                                    ->whereIn('id', $request->input('contestants'))
                                    ->orderBy('last_name')
                                    ->with(['memberships'])
                                    ->get();

        $rodeo->load(['entries', 'group_office_fee_exceptions']);

        $validated = $request->validate([

            'contestants' => [
                'required', 
                'array',
            ], 

            'contestants.*' => [
                function ($attribute, $value, $fail) use ($organizationContestants, $rodeo) {

                    $contestant = $organizationContestants->where('id', $value)->first();

                    if( !$contestant )
                    {
                        $fail('Invalid contestant id.');
                    }

                    elseif( $rodeo->entries->where('contestant_id', $value)->whereNotNull('checked_in_at')->count() > 0 )
                    {
                        $fail("{$contestant->lexical_name_order} has already been checked in.");
                    }

                },  
            ], 

            'memberships' => [
                'nullable', 
                'array',
            ], 

        ]);

        if( !isset($validated['memberships']) )
        {
            $validated['memberships'] = [];
        }

        $contestants = $organization
                        ->contestants()
                        ->whereIn('id', $validated['contestants'])
                        ->orderBy('last_name')
                        ->with(['memberships'])
                        ->get();

        // check if contestants are members,
        // and redirect to add membership if not
        if( !$request->input('memberships_checked', false)  &&  $rodeo->series_id  &&  $rodeo->series->membership_fee )
        {
            foreach( $contestants as $contestant )
            {
                $membership = $contestant->memberships->where('series_id', $rodeo->series_id)->first();

                if( $membership  &&  !$membership->paid )
                {
                    return redirect()
                            ->route('L3.check-in.add.memberships', [$organization, $rodeo, 'contestants' => $validated['contestants']]);
                }
            }
        }

        $rodeoEntries = $rodeo->entries;

        $entries = $rodeo
                    ->competition_entries()
                    ->with(['competition', 'competition.event', 'competition.group'])
                    ->whereIn('contestant_id', $validated['contestants'])
                    ->get();

        $rodeoEntries = $rodeo->entries()->whereIn('contestant_id', $validated['contestants'])->get();

        $oldCheckedInNotes = '';

        foreach ($rodeoEntries as $entry)
        {
            if ($entry->checked_in_notes && $entry->checked_in_notes != $oldCheckedInNotes)
            {
                $oldCheckedInNotes .= $entry->checked_in_notes;
            }
        }

        return view('L3.check-in.summary')
                ->with('organization', $organization)
                ->with('rodeo', $rodeo)
                ->with('contestants', $contestants)
                ->with('entries', $entries)
                ->with('rodeoEntries', $rodeoEntries)
                ->with('addMembershipToContestantIds', $validated['memberships'])
                ->with('oldCheckedInNotes', $oldCheckedInNotes);
    }


    public function addMemberships( Organization $organization, Rodeo $rodeo, Request $request )
    {
        if( $rodeo->organization_id != $organization->id )
        {
            abort(404);
        }

        $organizationContestants = $organization
                                    ->contestants()
                                    ->orderBy('last_name')
                                    ->with(['memberships'])
                                    ->get();

        $validated = $request->validate([

            'contestants' => [
                // 'required', 
                // 'array',
            ], 

            'contestants.*' => [
                // function ($attribute, $value, $fail) use ($organizationContestants, $rodeo) {

                //     $contestant = $organizationContestants->where('id', $value)->first();

                //     if( !$contestant )
                //     {
                //         $fail('Invalid contestant id.');
                //     }

                //     elseif( $rodeo->entries->where('contestant_id', $value)->whereNotNull('checked_in_at')->count() > 0 )
                //     {
                //         $fail("{$contestant->lexical_name_order} has already been checked in.");
                //     }

                // },  
            ], 
        ]);

        $contestants = Contestant::whereIn('id', $validated['contestants'])
                            ->orderBy('last_name')
                            ->with(['memberships'])
                            ->get();

        $membershipsAddable = [];

        if( $rodeo->series_id )
        {
            foreach( $contestants as $contestant )
            {
                $membership = $contestant->memberships->where('series_id', $rodeo->series_id)->first();

                if( !$membership  ||  !$membership->paid )
                {
                    $membershipsAddable[] = $contestant->id;
                }
            }
        }

        if( !$membershipsAddable )
        {
            return redirect()
                    ->route('L3.check-in.contestants', [$organization, $rodeo]);
        }

        return view('L3.check-in.add_memberships')
                ->with('organization', $organization)
                ->with('rodeo', $rodeo)
                ->with('contestants', $contestants)
                ->with('membershipsAddable', $membershipsAddable);
    }


    public function process( Organization $organization, Rodeo $rodeo, Request $request )
    {
        if( $rodeo->organization_id != $organization->id )
        {
            abort(404);
        }

        $submittedIds = is_array($request->input('contestants', []))
                            ?  $request->input('contestants', [])
                            :  [];

        $contestants = $organization->contestants()->whereIn('id', $submittedIds)->get();
        $rodeoEntries = $rodeo->entries;

        $validated = $request->validate([

            'contestants' => [
                'required', 
                'array',
            ], 


            'contestants.*' => [
                
                function ($attribute, $value, $fail) use ($contestants, $rodeoEntries) {

                    $contestant = $contestants->where('id', $value)->first();

                    if( null === $contestant )
                    {
                        $fail("Invalid contestant id.");
                    }
                    elseif( $rodeoEntries->where('contestant_id', $value)->whereNotNull('checked_in_at')->count() > 0 )
                    {
                        $fail("{$contestant->lexical_name_order} has already been checked in.");
                    }

                }, 
            ],


            'notes' => [
                'nullable'
            ],           

            'memberships' => [
                'nullable', 
                'array',
            ],
        ]);

        $rodeo
            ->entries()
            ->whereIn('contestant_id', $validated['contestants'])
            ->update([
                'checked_in_at' => \Carbon\Carbon::now(),
                'checked_in_notes' => isset($validated['notes']) ? $validated['notes'] : null
            ]);

        // update memberships as paid.. 
        if( $rodeo->series_id  &&  isset($validated['memberships']) )
        {
            $seriesMembershipContestantIds = Membership::where('series_id', $rodeo->series_id)->pluck('contestant_id')->toArray();

            foreach( $validated['contestants'] as $contestantId )
            {
                if( in_array($contestantId, $validated['memberships']) )
                {
                    if( in_array($contestantId, $seriesMembershipContestantIds) )
                    {
                        Membership::where('series_id', $rodeo->series_id)->where('contestant_id', $contestantId)->update([ 'paid' => true ]);
                    }
                    else
                    {
                        Membership::create([
                            'series_id' => $rodeo->series_id,
                            'contestant_id' => $contestantId,
                            'paid' => true,
                        ]);
                    }

                }
            }
        }

        return redirect()
                ->route('L3.check-in.contestants', [$organization->id, $rodeo->id])
                ->with('successAlert', 'Contestants checked in.');
    }


    public function deleteCheckIn( Organization $organization, RodeoEntry $entry )
    {
        $entry->load(['contestant', 'rodeo']);

        $contestant = $entry->contestant;
        $rodeo = $entry->rodeo;

        if( $organization->id !== $entry->rodeo->organization_id )
        {
            abort( 404 );
        }

        $entry->update([
            'checked_in_at' => null
        ]);

        return redirect()
                ->route( 'L3.check-in.checked.in', [$organization->id, $rodeo->id] )
                ->with('successAlert', "{$contestant->lexical_name_order} check in undone.");
    }
}
