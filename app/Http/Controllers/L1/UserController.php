<?php

namespace App\Http\Controllers\L1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Contracts\ListBuilder;
use Illuminate\Validation\Rule;
use App\User;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');

        $this->authorizeResource( User::class, 'user' );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( ListBuilder $service )
    {       
        $searchable = ['id', 'first_name', 'last_name'];
        $sortable   = ['id', 'first_name', 'last_name', 'email', 'created_at'];

        $service->setSearchableAttributes( $searchable );
        $service->setSortableAttributes( $sortable );

        $results = $service->getResultsForQuery( User::where('hidden', false) );

        $data['results'] = $results;
        $data['sortable'] = $sortable;

        return view('L1.users_index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('L1.users_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        $validated = $request->validate([
            'first_name' => [
                'required', 
                'string', 
                'max:255'
            ],
            
            'last_name' => [
                'required', 
                'string', 
                'max:255'
            ],

            'email' => [
                'required', 
                'string',
                'max:255',
                'email',  
                'unique:users'
            ],

            'password' => [
                'required', 
                'string', 
                'min:8', 
                'confirmed'
            ]
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
            'password'   => Hash::make( $validated['password'] ),
        ]);

        return redirect()
                ->route('admin.users.index')
                ->with("successAlert", "User created.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function show( User $user )
    {
        $user->load(['contestants', 'contestants.organization']);

        return view('L1.users_show')
                ->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  App\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit( User $user )
    {
        return view('L1.users_edit')
                ->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => [
                'required', 
                'string', 
                'max:255'
            ],
            
            'last_name' => [
                'required', 
                'string', 
                'max:255'
            ],

            'email' => [
                'required', 
                'string',
                'max:255',
                'email',  
                Rule::unique('users')->ignore($user->id),
            ]
        ]);

        $user->update([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
        ]);

        return redirect()
                ->route('admin.users.show', $user->id)
                ->with("successAlert", "User #{$user->id} updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( User $user )
    {
        $user->delete();

        return redirect()
                ->route('admin.users.index')
                ->with("successAlert", "User #{$user->id} - {$user->email} - deleted");
    }
}
