<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;
use Illuminate\Database\Eloquent\Model;

class Textarea extends Component
{
    public $name;
    public $value;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( $name, $value=null, $model=null )
    {
        $this->name = $name;

        if( null === $value  &&  null !== $model  &&  is_a($model, Model::class) )
        {
            $value = $model->$name;
        }

        $this->value = old($name, $value);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.form.textarea');
    }
}
