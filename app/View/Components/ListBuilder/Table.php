<?php

namespace App\View\Components\ListBuilder;

use Illuminate\View\Component;

/**
 * Show list in a table.
 *
 * Examples:
 *
 *      <x-list-builder.table :records="$results" :columns="[ 'id', 'name', 'created_at']" />
 *
 *      <x-list-builder.table :records="$results" :columns="['name', 'created_at']"> 
 *          <x-slot name="thead">
 *              <thead> <tr> <th> Name </th> <th> Created </th> </tr> </thead>
 *          </x-slot>
 *      </x-list-builder.table>
 *
 */
class Table extends Component
{
    public $records;
    public $columns;
    public $defaultHead; 

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( $records, array $columns, $defaultHead=false )
    {
        $this->records = $records;
        $this->columns = $columns;
        $this->defaultHead = $defaultHead;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.list-builder.table');
    }
}
