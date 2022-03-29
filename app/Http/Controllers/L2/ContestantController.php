<?php

namespace App\Http\Controllers\L2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Contracts\ListBuilder;
use App\Http\Requests\ContestantStore;
use App\Http\Requests\ContestantUpdate;
use App\Organization;
use App\Contestant;

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
        $this->middleware('level-2');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( ListBuilder $service, Organization $organization )
    {
        $searchable = [ 'first_name', 'last_name' ];
        $sortable = [ 'first_name', 'last_name' ];

        $service->setSearchableAttributes( $searchable );
        $service->setSortableAttributes( $sortable );

        $query = $organization->contestants();

        $query = $service->buildSearch( $query );

        if( null === $service->getSortDataFromRequest() )
        {
            $query = $query->orderBy('last_name')->orderBy('first_name');
        }
        else
        {
            $query = $service->buildSort( $query );
        }
        
        $contestants = $service->getPaginated( $query );

        $data['sortable'] = $sortable;
        $data['organization'] = $organization;
        $data['contestants'] = $contestants;
        $data['resultCount'] = null;// $request->input('search') ? $contestants->total() : null;
        
        return view('L2.contestants.contestants_index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( Organization $organization )
    {
        return view('L2.contestants.contestants_create')
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

        $validated['photo_path'] = isset($validated['profile_picture']) 
                                    ? $validated['profile_picture']->store('contestants', 'public')
                                    : null;

        unset($validated['profile_picture']);
        
        $validated['organization_id'] = $organization->id;

        $contestant = Contestant::create($validated);

        return redirect()
                ->route('L2.contestants.show', [$organization, $contestant])
                ->with('successAlert', 'Contestant added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contestant  $contestant
     * @return \Illuminate\Http\Response
     */
    public function show( Organization $organization, Contestant $contestant )
    {
        if( $organization->id != $contestant->organization_id )
        {
            abort(403);
        }

        $contestant->load([
            'competition_entries', 
            'competition_entries.competition', 
            'competition_entries.competition.event', 
            'competition_entries.competition.group'
        ]);

        $data['organization'] = $organization;
        $data['contestant'] = $contestant;
        $data['currentRodeos'] = $organization->rodeos()->notEnded()->get();
        
        return view('L2.contestants.contestants_show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contestant  $contestant
     * @return \Illuminate\Http\Response
     */
    public function edit( Organization $organization, Contestant $contestant )
    {
        if( $organization->id != $contestant->organization_id )
        {
            abort(403);
        }

        return view('L2.contestants.contestants_edit')
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
            abort(403);
        }

        $validated = $request->validated();

        $newPhotoPath = isset($validated['profile_picture']) 
                        ? $validated['profile_picture']->store('contestants', 'public')
                        : null;

        $validated['photo_path']  = $newPhotoPath ? $newPhotoPath : $contestant->photo_path; 

        unset($validated['profile_picture']);
   
        $contestant->update($validated);

        return redirect()
                ->route('L2.contestants.show', [$organization, $contestant])
                ->with('successAlert', 'Contestant details updated.');        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contestant  $contestant
     * @return \Illuminate\Http\Response
     */
    public function destroy( Organization $organization, Contestant $contestant )
    {
        if( $organization->id != $contestant->organization_id )
        {
            abort(403);
        }

        $contestant->delete();

        return redirect()
                ->route('L2.contestants.index', $organization)
                ->with('successAlert', 'Contestant deleted!');        
    }
}
