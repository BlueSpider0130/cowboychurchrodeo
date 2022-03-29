<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * User account page
     */
    public function index()
    {
        return view('user_account');
    }


    /**
     * Update user info
     */
    public function updateInfo(Request $request)
    {
        $validated = $request->validate([
            'first_name'     => 'required|max: 255',
            'last_name'      => 'required|max: 255',     
        ]); 

        $user = $request->user();
        $user->first_name = $validated['first_name'];
        $user->last_name  = $validated['last_name'];
        $user->save();

        return redirect()
                ->route('account.index')
                ->with('successAlert', 'Account updated.');
    }


    /**
     * Update user email
     */
    public function updateEmail(Request $request)
    {
        $validated = $request->validate([
            'email'      => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                'unique:users'
            ],
        ]);

        $user = $request->user();
        $user->email = $validated['email'];
        $user->save();

        return redirect()
                ->route('account.index')
                ->with('successAlert', 'Email updated.');
    }


    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'password'   => [
                'required', 
                'string', 
                'min:8', 
                'confirmed'
            ],   
        ]);

        $user = $request->user();
        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()
                ->route('account.index')
                ->with('successAlert', 'Password changed.');
    }
}
