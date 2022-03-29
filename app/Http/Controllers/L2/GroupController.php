<?php

namespace App\Http\Controllers\L2;

use App\Organization;
use App\Group;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Rules\UniqueForOrganization;

class GroupController extends Controller
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
    public function index( Request $request, Organization $organization )
    {
        $groups = $organization->groups()->orderBy('name')->get();

        if( $request->wantsJson() )
        {
            return $groups;
        }

        return view('L2.groups.groups_index')
                ->with('organization', $organization)
                ->with('groups', $groups);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Organization $organization)
    {
        return view('L2.groups.groups_create')
                ->with('organization', $organization);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Organization $organization)
    {
        $validated = $request->validate([
            
            'name' => [
                'required', 
                'string', 
                'max:255', 
                ( new \App\Rules\Unique( Group::class ) )->forOrganization( $organization )
            ],

            'description' => [
                'nullable', 
                'string'
            ]

        ]);

        $validated['organization_id'] = $organization->id;

        $group = Group::create($validated);

        return redirect()
                ->route('L2.groups.index', $organization)
                ->with('successAlert', "Group \"{$group->name}\" added.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Organization $organization, Group $group)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Organization $organization, Group $group)
    {
        return view('L2.groups.groups_edit')
                ->with('organization', $organization)
                ->with('group', $group);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Organization $organization, Group $group)
    {
        $validated = $request->validate([
            
            'name' => [
                'required', 
                'string', 
                'max:255', 
                ( new \App\Rules\Unique( Group::class ) )->forOrganization( $organization )->ignore($group)
            ],

            'description' => [
                'nullable', 
                'string'
            ]

        ]);

        $group->update( $validated );

        return redirect()
                ->route('L2.groups.index', $organization)
                ->with('successAlert', "Group \"{$group->name}\" updated.");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organization $organization, Group $group)
    {
        if( !Gate::allows('access-level-2-for-record', $group) )
        {
            abort(403);
        }

        $group->delete();

        return redirect()
                ->route('L2.groups.index', $organization)
                ->with('successAlert', 'Group deleted.');
    }
}
