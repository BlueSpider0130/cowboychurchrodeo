<?php

namespace App\Http\Controllers\L1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Contestant;
use App\User;

class ContestantAssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function unassign( Contestant $contestant, User $user )
    {
        $contestant->users()->detach($user->id);

        return redirect()
                ->route('admin.contestants.show', $contestant->id)
                ->with('successAlert', "Contestant unassigned from {$user->name}");
    }

    public function assign( Request $request, Contestant $contestant )
    {
        $userIds = User::pluck('id')->toArray();

        if( $users = $request->input('users') )
        {
            foreach ($users as $userId) 
            {
                if( in_array($userId, $userIds) )
                {
                    $contestant->users()->attach($userId);
                }
            }

            $request->session()->flash('successAlert', 'Contestant assignments updated');
        }

        return redirect()->route('admin.contestants.show', $contestant);
    }
}
