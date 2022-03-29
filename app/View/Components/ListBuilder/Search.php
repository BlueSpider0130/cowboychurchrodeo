<?php

namespace App\View\Components\ListBuilder;

use Illuminate\View\Component;
use Illuminate\Http\Request;

class Search extends Component
{
    public $key;
    public $formId;
    public $method;
    public $action;
    public $params = [];
    public $name;
    public $value;
    public $placeholder;
    public $showSearchButton;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( Request $request, $method='GET', $action='', $name='search', $placeholder=null, $id=null, $withQuerystring=false, $showSearchButton=true )
    {
        $this->key = uniqid();
        $this->formId = $id  ?  $id  :  "search-form-{$this->key}";

        $this->method = $method;
        $this->action = $action ? $action : $request->url();

        $this->name = $name  ?  $name  :  ListBuilder::SEARCH_PARAM;

        $this->placeholder = $placeholder;

        $this->value = $request->input($name);

        if( $withQuerystring )
        {
            $this->params = $request->query();

            unset($this->params['search']);     // the search parameter will be in the value and should not be added 
            unset($this->params['page']);       // running a new search should reset the pagination page to the start
        }

        $this->showSearchButton = $showSearchButton;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.list-builder.search');
    }
}
