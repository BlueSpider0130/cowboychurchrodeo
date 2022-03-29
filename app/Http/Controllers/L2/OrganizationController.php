<?php

namespace App\Http\Controllers\L2;

use App\Http\Controllers\Controller;
use App\Organization;
use Illuminate\Http\Request;
use App\Http\Requests\OrganizationSave;

class OrganizationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('level-2');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function show( Organization $organization )
    {
        return view('L2.organization.organization_show')
                ->with('organization', $organization);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function edit( Organization $organization )
    {
        return view('L2.organization.organization_edit')
                ->with('organization', $organization);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function update( OrganizationSave $request, Organization $organization )
    {
        $organization->update( $request->validated() );

        return redirect()
                ->route('producer.home', $organization->id)
                ->with('successAlert', "Organization details updated.");
    }

}
