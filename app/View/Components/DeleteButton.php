<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DeleteButton extends Component
{
    public $url;
    public $message; 
    public $key;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( $url, $message=null )
    {
        $this->url = $url;
        $this->message = $message ? $message : 'Are you sure you want to delete this item?';
        $this->key = uniqid();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.delete-button');
    }
}
