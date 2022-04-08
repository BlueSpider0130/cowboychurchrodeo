<?php

namespace App\Http\Controllers\L2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Organization;
use App\Series;
use App\Membership;

class MembershipController extends Controller
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
        $currentSeries = $organization
                            ->series()
                            ->with(['memberships'])
                            ->started()
                            ->notEnded()
                            ->orderBy('starts_at')
                            ->get();

        $allSeries = $organization
                            ->series()
                            ->with(['memberships'])
                            ->orderBy('starts_at', 'desc')
                            ->get();

        return view('L2.memberships.home')
                ->with('organization', $organization)
                ->with('currentSeries', $currentSeries)
                ->with('allSeries', $allSeries);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Organization $organization, Series $series )
    {
        if( $organization->id != $series->organization_id )
        {
            abort( 404 );
        }

        $memberships = $series
                        ->memberships()
                        ->with('contestant')
                        ->get()
                        ->sortBy('contestant.last_name');
                        // dd($series); exit();

        return view('L2.memberships.index')
                ->with('organization', $organization)
                ->with('series', $series)
                ->with('memberships', $memberships );

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
            abort( 404 );
        }

        $memberIds = $series->memberships()->pluck('contestant_id')->toArray();

        $contestants = $organization->contestants()->whereNotIn('id', $memberIds)->orderBy('last_name')->get();

        return view('L2.memberships.create')
                ->with('organization', $organization)
                ->with('series', $series)
                ->with('contestants', $contestants );        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Organization $organization, Series $series, Request $request )
    {
        if( $organization->id != $series->organization_id )
        {
            abort( 404 );
        }

        $contestantIds = $organization->contestants()->pluck('id')->toArray();
        $memberIds = $series->memberships()->pluck('contestant_id')->toArray();

        $validated = $request->validate([

            'contestant' => [
                'required', 
                \Illuminate\Validation\Rule::in( $contestantIds ),
                \Illuminate\Validation\Rule::notIn( $memberIds ),
            ], 

            'paid' => [
                'nullable'
            ]

        ]);

        Membership::create([
            'contestant_id' => $validated['contestant'],
            'series_id' => $series->id, 
            'paid' => isset($validated['paid']) ? true : false
        ]);

        return redirect()
                ->route('L2.memberships.index', [$organization->id, $series->id])
                ->with('successAlert', 'Member added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( Organization $organization, Series $series, Membership $membership, Request $request )
    {
        if( $organization->id != $series->organization_id )
        {
            abort( 404 );
        }

        if( $series->id != $membership->series_id )
        {
            abort( 404 );
        }

        return view('L2.memberships.show')
                ->with('organization', $organization)
                ->with('series', $series)
                ->with('membership', $membership );     
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( Organization $organization, Series $series, Membership $membership, Request $request )
    {
        if( $organization->id != $series->organization_id )
        {
            abort( 404 );
        }

        if( $series->id != $membership->series_id )
        {
            abort( 404 );
        }

        return view('L2.memberships.edit')
                ->with('organization', $organization)
                ->with('series', $series)
                ->with('membership', $membership );     
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update( Organization $organization, Series $series, Membership $membership, Request $request )
    {
        if( $organization->id != $series->organization_id )
        {
            abort( 404 );
        }

        if( $series->id != $membership->series_id )
        {
            abort( 404 );
        }

        $validated = $request->validate([

            'paid' => [
                'nullable'
            ]

        ]);

        $membership->update([
            'paid' => isset($validated['paid']) ? true : false
        ]);

        return redirect()
                ->route('L2.memberships.index', [$organization->id, $series->id])
                ->with('successAlert', 'Membership updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Organization $organization, Series $series, Membership $membership, Request $request )
    {
        if( $organization->id != $series->organization_id )
        {
            abort( 404 );
        }

        if( $series->id != $membership->series_id )
        {
            abort( 404 );
        }

        $membership->delete();

        return redirect()
                ->route('L2.memberships.index', [$organization->id, $series->id])
                ->with('successAlert', 'Membership deleted.');
    }
}
