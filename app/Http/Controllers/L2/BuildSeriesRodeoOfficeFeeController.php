<?php

namespace App\Http\Controllers\L2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Organization;
use App\Series;
use App\Rodeo;

class BuildSeriesRodeoOfficeFeeController extends Controller
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


    public function edit( Organization $organization, Series $series, Rodeo $rodeo )
    {
        if( $organization->id != $series->organization_id )
        {
            abort(403);
        }
        
        if( $organization->id != $rodeo->organization_id )
        {
            abort(403);
        }

        if( $series->id != $rodeo->series_id )
        {
            abort(404);
        }

        $data['organization'] = $organization;
        $data['series'] = $series;
        $data['rodeo'] = $rodeo;
        $data['rodeo'] = $rodeo;
        $data['events'] = $organization->events()->orderBy('name')->get()->sortBy('name', SORT_NATURAL);
        $data['groups'] = $organization->groups()->orderBy('name')->get()->sortBy('name', SORT_NATURAL);
        $data['notApplicableEventIds'] = $rodeo->event_office_fee_exceptions->pluck('id')->toArray();
        $data['notApplicableGroupIds'] = $rodeo->group_office_fee_exceptions->pluck('id')->toArray();
        
        return view('L2.build_series.rodeo_office_fee_edit', $data);
    }


    public function update( Request $request, Organization $organization, Series $series, Rodeo $rodeo )
    {
        if( $organization->id != $series->organization_id )
        {
            abort(403);
        }
        
        if( $organization->id != $rodeo->organization_id )
        {
            abort(403);
        }

        if( $series->id != $rodeo->series_id )
        {
            abort(404);
        }

        $availableGroupIds = $organization->groups->pluck('id')->toArray();
        $availableEventIds = $organization->events->pluck('id')->toArray();

        $validated = $request->validate([
            'groups' => [
                'nullable', 
                'array', 
            ], 
            'groups.*' => [
                \Illuminate\Validation\Rule::in($availableGroupIds),
            ],
            'events' => [
                'nullable', 
                'array', 
            ], 
            'events.*' => [
                \Illuminate\Validation\Rule::in($availableEventIds),
            ]
        ]);

        $groupIds = isset($validated['groups']) ? $validated['groups'] : [];
        $rodeo->group_office_fee_exceptions()->sync($groupIds);
        
        $eventIds = isset($validated['events']) ? $validated['events'] : [];
        $rodeo->event_office_fee_exceptions()->sync($eventIds);

        return redirect()
                ->route('L2.build.series.rodeos.show', [$organization->id, $series->id, $rodeo->id])
                ->with('successAlert', 'Office groups/events updated.');
    }
}
