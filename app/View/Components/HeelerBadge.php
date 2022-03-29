<?php

namespace App\View\Components;

use Illuminate\View\Component;

class HeelerBadge extends Component
{
    public $outline;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( $outline=false )
    {
        $this->outline = $outline;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.heeler-badge');
    }
}
