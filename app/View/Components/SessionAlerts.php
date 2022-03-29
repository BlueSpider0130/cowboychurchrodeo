<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Http\Request;

class SessionAlerts extends Component
{
    /**
     * The alert message.
     *
     * @var Illuminate\Session\Store 
     */
    public $session;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( Request $request )
    {
        $this->session = $request->session();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.session-alerts');
    }
}
