<?php

namespace App\Http\Controllers\L4;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Contestant;
use App\Membership;
use App\Organization;
use App\Series;

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
    }


    /**
     * Membership home.
     */
    public function home( Organization $organization, Request $request )
    {
        $contestantIds = $request->user()->contestants->pluck('id')->toArray();
        $contestantMembershipSeriesIds = Membership::whereIn('contestant_id', $contestantIds)->pluck('id')->toArray();

        $data['organization'] = $organization;

        $data['currentSeries'] = $organization
                                    ->series()
                                    ->started()
                                    ->notEnded()
                                    ->get();

        $data['previousSeries'] = Series::whereIn('id', $contestantMembershipSeriesIds)
                                    ->ended()
                                    ->get();

        return view('L4.membership.home', $data);
    }


    /**
     * Membership details for series.
     */
    public function details( Organization $organization, Series $series, Request $request )
    {
        if( $organization->id != $series->organization_id )
        {
            abort( 404 );
        }

        $userContestantIds = $request->user()->contestants->pluck('id')->toArray();
        
        $contestants = $organization->contestants()->whereIn('id', $userContestantIds)->orderBy('first_name')->get();

        return view('L4.membership.details')
                ->with( 'organization', $organization )
                ->with( 'series', $series )
                ->with( 'contestants', $contestants );
    }


    /**
     * Create membership for contestant
     */
    public function create( Organization $organization, Series $series, Contestant $contestant, Request $request )
    {
        if( $organization->id != $series->organization_id )
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

        if( $contestant->memberships->where('series_id', $series->id)->count() > 0 )
        {
            $seriesName = $series->name ? $series->name : "series #{$series->id}";
            $seriesDates = $series->starts_at  &&  $series->ends_at 
                                ?  '( ' . $series->starts_at->toFormattedDateString() . ' - ' . $series->ends_at->toFormattedDateString() . ' )'
                                : '';

            return view('common_error_page')
                    ->with('message', "{$contestant->name } is already a member for series \"{$seriesName}\" {$seriesDates}");
        }

        return view('L4.membership.create')
                ->with( 'organization', $organization )
                ->with( 'series', $series )
                ->with( 'contestant', $contestant );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Organization $organization, Series $series, Contestant $contestant, Request $request )
    {
        if( $organization->id != $series->organization_id )
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

        if( $contestant->memberships->where('series_id', $series->id)->count() > 0 )
        { 
            return redirect()
                    ->route('L4.membership.create', [$organization->id, $series->id, $contestant->id])
                    ->with('errorAlert', "{$contestant->name} is already a member for the series.");
        }

        Membership::create([
            'contestant_id' => $contestant->id, 
            'series_id' => $series->id
        ]);

        return redirect() 
                ->route('L4.membership.details', [$organization->id, $series->id])
                ->with('successAlert', "{$contestant->name} registered as member for series.");
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
