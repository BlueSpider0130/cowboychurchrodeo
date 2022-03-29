<?php

namespace App\View\Components\L3\CheckIn;

use Illuminate\View\Component;

class ContestantSelect extends Component
{
    public $organization;
    public $rodeo;
    public $contestantsByDay;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( $organization, $rodeo, $contestants )
    {
        $this->organization = $organization;
        $this->rodeo = $rodeo;
        $this->contestantsByDay = $contestants;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('L3.check-in.components.contestant-select');
    }
}
