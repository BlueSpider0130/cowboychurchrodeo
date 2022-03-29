<?php

namespace App\View\Components\Fields;

use Illuminate\View\Component;

class InstanceSelect extends Component
{
    public $instances;
    public $value;
    public $name;    
    public $label; 

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( $instances, $value=null, $name=null, $label=null )
    {
        $this->instances = $instances;
        $this->value = $value;
        $this->name = $name ? $name : 'instance';
        $this->label = $label ? $label : 'Day / time';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.fields.instance-select');
    }
}
