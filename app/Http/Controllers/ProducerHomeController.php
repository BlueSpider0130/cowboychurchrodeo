<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Organization;

class ProducerHomeController extends Controller
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


    public function index( Request $request, Organization $organization )
    {
        $data['organization'] = $organization;

        if( Gate::allows('access-level-2-for-organization', $organization) )
        {
            return view('L2.home', $data);
        }

        if( Gate::allows('access-level-3-for-organization', $organization) )        
        {
            return view('L3.home', $data);
        }
        
        abort(403, 'You must have secretary or data entry access for this organization.');
    }    
}
