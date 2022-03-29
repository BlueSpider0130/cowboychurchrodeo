<?php

namespace App\Http\Controllers\L1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Contracts\ListBuilder;
use App\Http\Requests\OrganizationSave;
use App\Organization;


class OrganizationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( ListBuilder $service )
    {
        $searchable = [ 'name' ];
        $sortable = [ 'name' ];

        $service->setSearchableAttributes( $searchable );
        $service->setSortableAttributes( $sortable );

        $results = $service->getResultsForModelClass( Organization::class );

        $data['results'] = $results;
        $data['sortable'] = $sortable;

        return view('L1.organizations_index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('L1.organizations_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( OrganizationSave $request )
    {
        $validated = $request->validated();

        $organization = Organization::create( $validated );

        return redirect()
                ->route('admin.organizations.show', $organization->id)
                ->with('successAlert', "Organization \"{$validated['name']}\" created.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {
        $organization = Organization::with(['user_levels', 'user_levels.user'])
                            ->where('id', $id)
                            ->firstOrFail();

        return view('L1.organizations_show')
                ->with('organization', $organization);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( Organization $organization )
    {
        return view('L1.organizations_edit')
                ->with('organization', $organization);    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update( OrganizationSave $request, Organization $organization )
    {
        $validated = $request->validated();

        $organization->update( $validated );

        return redirect()
                ->route('admin.organizations.show', $organization->id)
                ->with('successAlert', "Organization updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Organization $organization )
    {
        $id = $organization->id;
        $organization->delete();

        return redirect()->route('admin.organizations.index')->with('successAlert', "Organization #{$id} deleted.");
    }
}
