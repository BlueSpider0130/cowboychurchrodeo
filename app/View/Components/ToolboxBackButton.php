<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ToolboxBackButton extends Component
{
    public $organization;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( $organization )
    {
        $this->organization = $organization;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.toolbox-back-button');
    }
}
