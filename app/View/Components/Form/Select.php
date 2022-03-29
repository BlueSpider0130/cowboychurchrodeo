<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class Select extends Component
{
    public $name;
    public $options;
    public $selected;
    public $placeholder;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( $name, array $options, $value=null, $placeholder=null )
    {
        $this->name = $name;
        $this->options = $options;
        $this->selected = old($name, $value);
        $this->placeholder = $placeholder;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.form.select');
    }
}
