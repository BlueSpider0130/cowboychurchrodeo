<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Http\Request;
use App\User;

class Operator extends Component
{
    /**
     * The user being operated.
     *
     * @var \App\User
     */
    public $user = null;

    /**
     * The operator user. 
     *
     * @var \App\User
     */
    public $operator = null;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( Request $request )
    {
        $this->user = $request->user();

        if( $request->session()->has('operator') )
        {
            $sessionUserId = $request->session()->get('operator')->id;

            $this->operator = User::find($sessionUserId);
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.operator');
    }
}
