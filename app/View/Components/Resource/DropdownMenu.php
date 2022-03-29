<?php

namespace App\View\Components\Resource;

use Illuminate\View\Component;


/**
 * Basic dropdown links (edit, delete, etc.) for a resource.
 *
 */
class DropdownMenu extends Component
{
    public $key;
    public $editUrl;
    public $deleteUrl;
    public $deleteMessage;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( $editUrl=null, $deleteUrl=null, $deleteMessage=null )
    {
        $this->key = uniqid();
        $this->editUrl = $editUrl;
        $this->deleteUrl = $deleteUrl;
        $this->deleteMessage = $deleteMessage ? $deleteMessage : "Are you sure you want to delete this item?";
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.resource.dropdown-menu');
    }
}
