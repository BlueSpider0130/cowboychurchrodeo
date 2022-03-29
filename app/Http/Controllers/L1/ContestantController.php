<?php

namespace App\Http\Controllers\L1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Contracts\ListBuilder;
use App\Contestant;
use Illuminate\Support\Facades\DB;
use App\User;

class ContestantController extends Controller
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
        $searchable = [ 'first_name', 'last_name' ];
        $sortable = [ 'organization_name', 'first_name', 'last_name' ];

        $service->setSearchableAttributes( $searchable );
        $service->setSortableAttributes( $sortable );

        $query = Contestant::select();
        // ( DB::raw('*, organizations.name as organization_name') )
        //             ->join('organizations', 'contestants.organization_id', '=', 'organizations.id')
        //             ->with('organization');

        $query = $service->buildSearch( $query );

        if( null === $service->getSortDataFromRequest() )
        {
            // $query = $query->orderBy('organization_name')->orderBy('last_name')->orderBy('first_name');
        }
        else
        {
            $query = $service->buildSort( $query );
        }
        
        $contestants = $service->getPaginated( $query );

        $data['sortable'] = $sortable;
        $data['contestants'] = $contestants;
        $data['resultCount'] = null;
        
        return view('L1.contestants.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( Contestant $contestant )
    {
        $availableUsers = User::whereNotIn('id', $contestant->users()->pluck('users.id')->toArray())
                            ->orderBy('last_name')
                            ->orderBy('first_name')
                            ->orderBy('email')
                            ->get();

        return view('L1.contestants.show')
                ->with('contestant', $contestant)
                ->with('users', $availableUsers);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
