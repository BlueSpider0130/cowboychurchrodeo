<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class ImageInput extends Component
{
    public $name;
    public $key;
    public $value; 
    public $imageClass;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( $name, $value=null, $imageClass=null )
    {
        $this->name = $name;
        $this->key = uniqid();
        $this->value = $value;
        $this->imageClass = $imageClass;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.form.image-input');
    }
}
