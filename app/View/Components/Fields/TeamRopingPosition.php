<?php

namespace App\View\Components\Fields;

use Illuminate\View\Component;

class TeamRopingPosition extends Component
{
    public $value; 

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( $value = null )
    {
        $this->value = strtolower($value);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.fields.team-roping-position');
    }
}
