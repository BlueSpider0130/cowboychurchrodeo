<?php

namespace App\Http\Controllers\L1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;


class UserOperatorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('end');
        $this->middleware('admin')->except('end');
    }


    public function start( Request $request, $id )
    {
        // make sure request user exits
        if( User::where('id', $id)->count() < 1 )
        {
            return view('L1.common_error_page')->with('message', "User  $id  not found");
        }

        // there is not an operator in the session 
        // and the requested user is the same as the logged in user
        // then nothing to do since the user is already operating as themself
        if( !$request->session()->has('operator')  &&  $id == $request->user()->id )
        {
            return view('L1.common_alert_page', [
                'message' => "You are already you... Let's not get too philosophical about it..."
            ]);
        }

        // if there is an operator in the session 
        // and the request user id is the same as the logged in user 
        // then nothing to do since they are already operating as that user
        if( $request->session()->has('operator')  &&  $id == $request->user()->id )
        {
            return redirect()->route('home');
        }

        // determine who the operating user should be 
        // if there is already an operator in the session then user that as the operator
        // otherwise the logged in user is the operator
        $operator = $request->session()->has('operator') 
                        ? User::find( $request->session()->get('operator')->id ) 
                        : $request->user();

        // if the id of operator user does not exist (i.e. did not find a user for the operator id in the session...)
        // then log user out and show an error message 
        // (this should not happen unless the operator user was deleted while they were logged in and operating as another user...)
        if( null === $operator )
        {            
            Auth::logout();
            return view('common_error_page')->with('message', "Operator lost...");
        }

        // if the requested user is the same as the operator user 
        // then this is the same as the operating user returning to being themselves... 
        if( $id == $operator->id )
        {
            return $this->end( $request );
        }

        // before logging is as the requested user 
        // there needs to be zn operator key to use to log user back in when they stop operating as the other user...
        // check the operator user to see if there is a valid key 
        // and if not generate one
        if( !$operator->operator_key  ||  !$operator->operator_key_expires_at  ||  $operator->operator_key_expires_at->greaterThan(\Carbon\Carbon::now()) )
        {
            $operator->operator_key = md5( random_bytes(128) );
        }
        
        // make the key expire in same amout of time as the password timeout value in config 
        $operator->operator_key_expires_at = \Carbon\Carbon::now()->addSeconds( config('auth.password_timeout') );

        $operator->save();

        // operator info to put in session 
        $operatorSessionInfo = (object) [
            'id' => $operator->id, 
            'key' => $operator->operator_key
        ];

        // log out and log back in as the requested user
        Auth::logout();        
        Auth::loginUsingId( $id );    

        // put operator info in the session... 
        $request->session()->put('operator', $operatorSessionInfo);

        return redirect('home');
    }



    public function end( Request $request )
    {
        if( $request->session()->has('operator') )
        {
            $operatorSessionInfo = $request->session()->get('operator');

            $request->session()->forget('operator');
            Auth::logout();

            // validate the operator info and log user back in as the operator if valid
            if( $user = User::find( $operatorSessionInfo->id ) )
            {
                if( null !== $user->operator_key  &&  $user->operator_key == $operatorSessionInfo->key  &&  null !== $user->operator_key_expires_at  &&  $user->operator_key_expires_at->greaterThan(\Carbon\Carbon::now()) )
                {
                    Auth::loginUsingId( $user->id );    
                }

                $request->session()->flash('alert', ['message' => "You are operating as yourself now. ({$user->getName()})", 'type' => 'info']);
            }            
        }

        return redirect()->route('admin.users.index');
    }    
}
