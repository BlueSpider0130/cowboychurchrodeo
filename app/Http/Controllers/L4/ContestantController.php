<?php

namespace App\Http\Controllers\L4;

use App\Organization;
use App\Contestant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContestantStore;
use App\Http\Requests\ContestantUpdate;
use Illuminate\Support\Facades\Storage;


class ContestantController extends Controller
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
     * Display a listing of the user's contestants.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Organization $organization, Request $request )
    {
        $contestants = $request
                        ->user()
                        ->contestants()
                        ->where('organization_id', $organization->id)
                        ->orderBy('first_name')
                        ->get();

        return view('L4.contestants.index')
                ->with('organization', $organization)
                ->with('contestants', $contestants);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( Organization $organization )
    {
        return view('L4.contestants.create')
                ->with('organization', $organization);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Organization $organization, ContestantStore $request )
    {
        $validated = $request->validated();

        $validated['organization_id'] = $organization->id; 

        $validated['photo_path'] = null;

        if( isset($validated['profile_picture']) )
        {
            $validated['photo_path'] = $validated['profile_picture']->store('contestants', 'public');
            unset( $validated['profile_picture'] );
        }
               
        $contestant = Contestant::create( $validated );

        $contestant->users()->attach( $request->user()->id );

        return redirect()
                ->route('L4.contestants.index', $organization)
                ->with('successAlert', 'Contestant added.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contestant  $contestant
     * @return \Illuminate\Http\Response
     */
    public function edit( Organization $organization, Contestant $contestant, Request $request )
    {
        if( $organization->id != $contestant->organization_id )
        {
            abort(400);
        }

        if( $contestant->users()->wherePivot('user_id', $request->user()->id)->count() < 1 )
        {
            abort(403);
        }

        return view('L4.contestants.edit')
                ->with('organization', $organization)
                ->with('contestant', $contestant);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contestant  $contestant
     * @return \Illuminate\Http\Response
     */
    public function update( Organization $organization, Contestant $contestant, ContestantUpdate $request )
    {
        if( $organization->id != $contestant->organization_id )
        {
            abort(400);
        }
        
        if( $contestant->users()->wherePivot('user_id', $request->user()->id)->count() < 1 )
        {
            abort(403);
        }

        $validated = $request->validated();

        $validated['photo_path'] = null;

        if( isset($validated['profile_picture']) )
        {
            $validated['photo_path'] = $validated['profile_picture']->store('contestants', 'public');
            unset( $validated['profile_picture'] );
        }
   
        $contestant->update( $validated );

        return redirect()
                ->route('L4.contestants.index', $organization)
                ->with('successAlert', 'Contestant details updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contestant  $contestant
     * @return \Illuminate\Http\Response
     */
    public function destroy( Organization $organization, Contestant $contestant, Request $request )
    {
        if( $organization->id != $contestant->organization_id )
        {
            abort(400);
        }

        if( $contestant->users()->wherePivot('user_id', $request->user()->id)->count() < 1 )
        {
            abort(403);
        }

        \App\Services\ContestantService::delete( $contestant );

        return redirect()
                ->route('L4.contestants.index', $organization)
                ->with('successAlert', 'Contestant deleted!');
    }
}
