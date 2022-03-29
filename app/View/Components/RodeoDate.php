<?php

namespace App\View\Components;

use Illuminate\View\Component;

class RodeoDate extends Component
{
    public $date;
    public $default;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( \Carbon\Carbon $date = null, $default = null )
    {
        $this->date = $date;
        $this->default = $default;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.rodeo-date');
    }
}
