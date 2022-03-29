<?php

namespace App\Http\Controllers\L2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Rules\UniqueForOrganization;
use App\Rules\UniqueForOrganizationId;
use App\Rules\MoneyFormat;
use App\Rules\MoneyMax;
use App\Organization;
use App\Series;

class BuildSeriesController extends Controller
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
        return $this->index( $organization );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Organization $organization )
    {
        $data['organization'] = $organization;

        return view('L2.build_series.series_index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( Organization $organization )
    {
        $data['organization'] = $organization;
        
        return view('L2.build_series.series_create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request, Organization $organization )
    {
        $validated = $request->validate([

            'name' => [
                'required',
                'max:255',
                ( new \App\Rules\Unique( Series::class ) )->forOrganization( $organization )
            ], 

            'description' => [
                'nullable'
            ],

            'start_date' => [
                'nullable', 
                'date'
            ], 

            'end_date' => [
                'nullable',
                'date', 
                'after_or_equal:start_date'
            ],

            'membership_fee' => [
                'nullable', 
                new MoneyFormat(), 
                new MoneyMax(9999999999.99, '$', 'prepend')
            ]

        ]);

        $validated['organization_id'] = $organization->id;

        $validated['starts_at'] = $validated['start_date'];
        $validated['ends_at'] = $validated['end_date'];

        unset($validated['start_date']);
        unset($validated['end_date']);


        $series = Series::create($validated);

        return redirect()
                ->route('L2.build.series.show', [$organization, $series])
                ->with('successAlert', "Series {$series->name} created.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Series  $series
     * @return \Illuminate\Http\Response
     */
    public function show( Organization $organization, Series $series )
    {
        if( $organization->id != $series->organization_id )
        {
            abort(403);
        }

        $data['organization'] = $organization;
        $data['series'] = $series;

        return view('L2.build_series.series_show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Series  $series
     * @return \Illuminate\Http\Response
     */
    public function edit( Organization $organization, Series $series )
    {
        if( $organization->id != $series->organization_id )
        {
            abort( 403 );
        }

        $data['organization'] = $organization;
        $data['series'] = $series;

        return view('L2.build_series.series_edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Series  $series
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Organization $organization, Series $series)
    {
        if( $organization->id != $series->organization_id )
        {
            abort( 403 );
        }

        $validated = $request->validate([

            'name' => [
                'required',
                'max:255',
                ( new \App\Rules\Unique( Series::class ) )->forOrganization( $organization )->ignore( $series )
            ], 

            'description' => [
                'nullable'
            ],

            'start_date' => [
                'nullable', 
                'date'
            ], 

            'end_date' => [
                'nullable',
                'date', 
                'after_or_equal:start_date'
            ],

            'membership_fee' => [
                'nullable', 
                new MoneyFormat(), 
                new MoneyMax(9999999999.99, '$', 'prepend')
            ]

        ]);

        $validated['starts_at'] = $validated['start_date'];
        $validated['ends_at'] = $validated['end_date'];

        unset($validated['start_date']);
        unset($validated['end_date']);

        $series->update($validated);

        return redirect()
                ->route('L2.build.series.show', [$organization, $series])
                ->with('successAlert', "Series updated.");        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Series  $series
     * @return \Illuminate\Http\Response
     */
    public function destroy( Organization $organization, Series $series )
    {
        if( $organization->id != $series->organization_id )
        {
            abort( 403 );
        }

        $series->delete();

        return redirect()
                ->route('L2.build.series.index', [$organization, $series])
                ->with('successAlert', "Series deleted.");  
    }
}
