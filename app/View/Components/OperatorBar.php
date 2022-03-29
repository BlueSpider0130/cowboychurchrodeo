<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Http\Request;
use App\User;

class OperatorBar extends Component
{
    /**
     * The operator user. 
     *
     * @var \App\User
     */
    public $operator = null;

    /**
     * The user being operated.
     *
     * @var \App\User
     */
    public $user = null;

    /**
     * If the operating as should be shown;
     *
     * @var boolean
     */
    public $showOperatingAs = false;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( Request $request, $show = false )
    {
        $this->user = $request->user();

        if( $request->session()->has('operator') )
        {
            $sessionUserId = $request->session()->get('operator')->id;

            $this->operator = User::find($sessionUserId);
        }

        $this->showOperatingAs = $this->user && $this->operator ? true : false;

        if( $show )
        {
            $this->showOperatingAs = true;
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.operator-bar');
    }
}
