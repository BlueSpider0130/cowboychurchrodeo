<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $organizations = \App\Organization::orderBy('name')->paginate();

        if( 1 === $organizations->count() )
        {
            return redirect()
                    ->route('toolbox', $organizations->first()->id);
        }

        return view('home')
                ->with('organizations', $organizations);
    }


    /**
     * Show the toolbox.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function toolbox( \App\Organization $organization )
    {
        return view('toolbox')
                ->with('organization', $organization);
    }

}
