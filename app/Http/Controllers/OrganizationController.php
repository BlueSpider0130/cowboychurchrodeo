<?php

namespace App\Http\Controllers;

use App\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        $query = Organization::query();

        $search = trim( $request->input('search') );

        if( $search )
        {
            $query->where('name', 'like', "{$search}%");
        }

        $data['search'] = $search;
        $data['organizations'] = $query->orderBy('name')->paginate();

        return view('organizations.index', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function show(Organization $organization)
    {
        $data['organization'] = $organization;        
        $data['current'] = $organization->series()->inProgress()->whereNotNull('starts_at')->whereNotNull('ends_at')->get();
        $data['upcoming'] = $organization->series()->notStarted()->whereNotNull('starts_at')->whereNotNull('ends_at')->get();
        $data['previous'] = $organization->series()->ended()->whereNotNull('starts_at')->whereNotNull('ends_at')->get();
  
        return view('organizations.show', $data);
    }
}
