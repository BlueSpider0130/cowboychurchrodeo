<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class Buttons extends Component
{
    /**
     * Name to use for submit button.
     *
     * @var string
     */
    public $submitName = "Submit";

    /**
     * Name to use for cancel button.
     *
     * @var string
     */
    public $cancelName = "Cancel";

    /**
     * Url for cancel button. 
     *
     * @var string
     */
    public $cancelUrl = null;

    /**
     * Boolean to indicate if cancel button should be shown.
     *
     * @var boolean
     */
    public $showCancelButton = false;  

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( string $submitName = null, string $cancelName = null, string $cancelUrl = null )
    {
        if( null !== $submitName )
        {
            $this->submitName = $submitName;
        }

        if( null !== $cancelName )
        {
            $this->cancelName = $cancelName;
        }

        if( null !== $cancelUrl )
        {
            $this->cancelUrl = $cancelUrl;
        }

        $this->showCancelButton = $this->cancelUrl ? true : false;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.form.buttons');
    }
}
