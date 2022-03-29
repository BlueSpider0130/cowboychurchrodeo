<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Database\Eloquent\Model;

class RodeoDates extends Component
{
    public $model;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( Model $model )
    {
        $this->model = $model;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.rodeo-dates');
    }
}
