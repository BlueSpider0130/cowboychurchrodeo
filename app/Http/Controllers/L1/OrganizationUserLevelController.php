<?php

namespace App\Http\Controllers\L1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Organization;
use App\User;
use App\UserLevel;

class OrganizationUserLevelController extends Controller
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
    public function index( Organization $organization, $level )
    {
        if( !in_array($level, [2, 3]) )
        {
            abort( 404 );
        }

        $data['level'] = $level;
        $data['userLevels'] = UserLevel::withLevel($level)->forOrganization( $organization )->orderBy('updated_at', 'desc')->get();
        $data['organization'] = $organization;
        
        return view('L1.organization_user_level_index', $data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Organization $organization, $level )
    {
        if( !in_array($level, [2, 3]) )
        {
            abort( 404 );
        }

        $user = null;

        if( $email = $request->input('email') )
        {
            $user = User::where('email', $email)->first();
        }

        if( $userId = $request->input('user_id') )
        {
            $user = User::find($userId);
        }

        $hasLevel2 = $user && UserLevel::where('user_id', $user->id)->forOrganization($organization)->withLevel($level)->count() > 0 
                        ? true 
                        : false;

        $request->validate([
            'user_id' => [
                'nul lable', 
                function ($attribute, $value, $fail) use ($user, $hasLevel2, $level) {
                    
                    if( !$user ) 
                    {
                        $fail("User #{$value} not found.");
                    }

                    if( $hasLevel2 )
                    {
                        $fail("{$user->getName()} - {$user->email} - is already a level {$level} user.");
                    }
                },
            ],
            'email' => [
                'nullable', 
                'email', 
                'required_without:user_id',
                function ($attribute, $value, $fail) use ($user, $hasLevel2, $level) {
                    
                    if( !$user ) 
                    {
                        $fail('There is no user with that email.');
                    }

                    if( $hasLevel2 )
                    {
                        $fail("{$user->getName()} - {$user->email} - is already a level {$level} user.");
                    }
                },
            ]
        ]);

        // validation should have returned error if there is no user...
        if( !$user )
        {
            throw new \Exception("User not set...", 1);            
        }

        // grant level access to user 
        UserLevel::create([
            'user_id' => $user->id,
            'organization_id' => $organization->id, 
            'level' => $level,
            'enabled' => true,
        ]);

        return redirect()
                ->route('admin.organization.user.level.index', [$organization->id, $level])
                ->with('successAlert', "{$user->getName()} added to Level 2 for {$organization->name}.");
    
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserLevel  $userLevel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Organization $organization, $level, User $user )
    {
        UserLevel::forOrganization( $organization )->withLevel($level)->forUser($user)->delete();

        return redirect()
                ->route('admin.organization.user.level.index', [$organization->id, $level])
                ->with('successAlert', "Level 2 access removed from {$user->getName()}.");
    }
}
