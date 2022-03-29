<?php

namespace App\Http\Controllers\L2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Rules\MoneyFormat;
use App\Rules\MoneyMax;
use App\Organization;
use App\Series;
use App\Rodeo;

class BuildSeriesRodeoController extends Controller
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
    public function index()
    {
        abort(400);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( Organization $organization, Series $series )
    {
        if( $organization->id != $series->organization_id )
        {
            abort(403);
        }    

        return view('L2.build_series.rodeo_create')
                ->with('organization', $organization)
                ->with('series', $series);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request, Organization $organization, Series $series )
    {
        if( $organization->id != $series->organization_id )
        {
            abort(403);
        }

        $validated = $request->validate([

            'name' => [
                'required',
                'max:255',
                ( new \App\Rules\Unique( 'rodeos' ) )->forOrganization( $organization )
            ], 

            'description' => [
                'nullable'
            ],

            'start_date' => [
                'nullable', 
                'date', 
                new \App\Rules\SeriesDatesRequired( $series ),
                new \App\Rules\ValidForSeriesDates( $series ),
            ], 

            'end_date' => [
                'nullable',                
                'date', 
                'required_with:start_date',                
                'after_or_equal:start_date',                
                new \App\Rules\SeriesDatesRequired( $series ),
                new \App\Rules\ValidForSeriesDates( $series ),
            ],

            'office_fee' => [
                'nullable', 
                new MoneyFormat(), 
                new MoneyMax(9999999999.99, '$', 'prepend')
            ], 

            'open_time' => [
                'nullable', 
                'date', 
                'before:start_date'
            ], 

            'close_time' => [
                'nullable',
                'date', 
                'before_or_equal:start_date',
            ],

        ]);

        // rename form fields to attributes 
        $validated['starts_at'] = $validated['start_date'];
        $validated['ends_at'] = $validated['end_date'];

        unset($validated['start_date']);
        unset($validated['end_date']);

        // convert open and close to carbon dates if they are set...
        $validated['opens_at'] = isset($validated['open_time']) && $validated['open_time'] 
                                    ? \Carbon\Carbon::create( $validated['open_time'] )
                                    : null;
        $validated['closes_at'] = isset($validated['close_time']) && $validated['close_time'] 
                                    ? \Carbon\Carbon::create( $validated['close_time'] )
                                    : null;

        unset($validated['open_time']);
        unset($validated['close_time']);

        $validated['organization_id'] = $organization->id;
        $validated['series_id'] = $series->id;
                                    
        $rodeo = Rodeo::create($validated);

        return redirect()
                ->route('L2.build.series.rodeos.show', [$organization, $series, $rodeo])
                ->with('successAlert', 'Rodeo created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Rodeo  $rodeo
     * @return \Illuminate\Http\Response
     */
    public function show( Organization $organization, Series $series, Rodeo $rodeo )
    {
        if( $organization->id != $series->organization_id )
        {
            abort(403);
        }

        if( $organization->id != $rodeo->organization_id )
        {
            abort(403);
        }

        $rodeo->load(['competitions', 'competitions.instances']);

        $data['organization'] = $organization;
        $data['series'] = $series;
        $data['rodeo'] = $rodeo;
        $data['events'] = $organization->events()->orderBy('name')->get()->sortBy('name', SORT_NATURAL);
        $data['groups'] = $organization->groups()->orderBy('name')->get()->sortBy('name', SORT_NATURAL);
        $data['competitions'] = $rodeo->competitions;

        return view('L2.build_series.rodeo_show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Rodeo  $rodeo
     * @return \Illuminate\Http\Response
     */
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

        $data['organization'] = $organization;
        $data['series'] = $series;
        $data['rodeo'] = $rodeo;

        return view('L2.build_series.rodeo_edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Rodeo  $rodeo
     * @return \Illuminate\Http\Response
     */
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

        $validated = $request->validate([

            'name' => [
                'required',
                'max:255',
                ( new \App\Rules\Unique( 'rodeos' ) )->forOrganization( $organization )->ignore( $rodeo ),
            ], 

            'description' => [
                'nullable'
            ],

            'start_date' => [
                'nullable', 
                'date', 
                new \App\Rules\SeriesDatesRequired( $series ),
                new \App\Rules\ValidForSeriesDates( $series ),
            ], 

            'end_date' => [
                'nullable',                
                'date', 
                'required_with:start_date',                
                'after_or_equal:start_date',                
                new \App\Rules\SeriesDatesRequired( $series ),
                new \App\Rules\ValidForSeriesDates( $series ),
            ],

            'office_fee' => [
                'nullable', 
                new MoneyFormat(), 
                new MoneyMax(9999999999.99, '$', 'prepend')
            ], 

            'open_time' => [
                'nullable', 
                'date', 
                'before:start_date'
            ], 

            'close_time' => [
                'nullable',
                'date', 
                'before_or_equal:start_date',
            ],

        ]);

        // rename form fields to attribute names and convert to carbon dates
        $validated['starts_at'] =  \Carbon\Carbon::create( $validated['start_date'] );
        $validated['ends_at'] =  \Carbon\Carbon::create( $validated['end_date'] );

        unset($validated['start_date']);
        unset($validated['end_date']);

        // convert open and close to carbon dates if they are set...
        $validated['opens_at'] = isset($validated['open_time']) && $validated['open_time'] 
                                    ? \Carbon\Carbon::create( $validated['open_time'] )
                                    : null;
        $validated['closes_at'] = isset($validated['close_time']) && $validated['close_time'] 
                                    ? \Carbon\Carbon::create( $validated['close_time'] )
                                    : null;

        unset($validated['open_time']);
        unset($validated['close_time']);

        $rodeo->update($validated);

        return redirect()
                ->route('L2.build.series.rodeos.show', [$organization, $series, $rodeo])
                ->with('successAlert', 'Rodeo updated.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Rodeo  $rodeo
     * @return \Illuminate\Http\Response
     */
    public function destroy( Organization $organization, Series $series, Rodeo $rodeo)
    {
        if( $organization->id != $series->organization_id )
        {
            abort(403);
        }

        if( $organization->id != $rodeo->organization_id )
        {
            abort(403);
        }

        $rodeo->delete();

        return redirect()
                ->route('L2.build.series.show', [$organization, $series])
                ->with('successAlert', "Rodeo deleted.");          
    }


    public function order( Organization $organization, Series $series, Rodeo $rodeo)
    {
        if( $organization->id != $series->organization_id )
        {
            abort(403);
        }

        if( $organization->id != $rodeo->organization_id )
        {
            abort(403);
        }

        $data['competitions'] = $rodeo->competitions()->orderBy('order')->get();

        $data['organization'] = $organization;
        $data['series'] = $series;
        $data['rodeo'] = $rodeo;

        return view('L2.build_series.rodeo_order', $data);       
    }

    public function saveOrder( Request $request, Organization $organization, Series $series, Rodeo $rodeo)
    {
        if( $organization->id != $series->organization_id )
        {
            abort(403);
        }

        if( $organization->id != $rodeo->organization_id )
        {
            abort(403);
        }

        $competitionIds = $rodeo->competitions()->pluck('id')->toArray();

        $validated = $request->validate([
            'order' => [
                'required',
                'array',
                function ($attribute, $value, $fail) use ($competitionIds) {
                    foreach($value as $competitionId => $order)
                    {
                        if ( !in_array($competitionId, $competitionIds) ) {
                            $fail("The event {$competitionId} is invalid for this rodeo.");
                        }
                    }
                },
                'bail'
            ],
            'order.*' => ['bail', 'required', 'numeric', 'integer', 'distinct']
        ]);

        \DB::transaction(function () use ($validated, $rodeo) {     
            asort($validated['order']);
            $count = 1;
            foreach($validated['order'] as $competitionId => $order)
            {
                \App\Competition::where('id', $competitionId)->update(['order' => $count]);
                $count++;
            }
        });

        return redirect()
            ->route('L2.build.series.rodeos.order', [$organization, $series, $rodeo])
            ->with('successAlert', 'Event order updated.');
    }
}
