<?php

namespace App\View\Components;

use Illuminate\View\Component;

class RodeoDateTime extends Component
{
    public $date;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( \Carbon\Carbon $date = null )
    {
        $this->date = $date;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.rodeo-date-time');
    }
}
