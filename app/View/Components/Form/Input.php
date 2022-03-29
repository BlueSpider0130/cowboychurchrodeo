<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class Input extends Component
{
    public $name;
    public $value;
    public $type;
    public $withError;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( $name, $type="text", $value=null,  $model=null, $attribute=null, $withError=true )
    {
        $this->name = $name;       
        $this->type = $type;
        $this->value = $value;

        if( null === $value  &&  null !== $model  &&  is_a($model, Model::class) )
        {
            $attribute = $attribute ? $attribute : $name; 
            $value = $model->$attribute;
        }

        if( null !== $value  &&  $value  &&  is_object($value)  &&  is_a($value, \Carbon\Carbon::class) )
        {
            if( 'date' == $type )
            {
                $value = $value->format('Y-m-d');
            }

            if( 'datetime-local' == $type )
            {
                $value = $value->format('Y-m-d').'T'.$value->format('H:i');
            }
        }

        $this->value = old($name, $value);

        $this->withError = $withError;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.form.input');
    }
}
