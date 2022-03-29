<?php

namespace App\View\Components\ListBuilder;

use Illuminate\View\Component;

/**
 * Sort by link as a table header <th> element.
 *
 * Example:
 *
 *      <x-list-builder.sort-by-table-header sort-by="name"> Name </x-list-builder.sort-by-table-header>
 *
 */
class SortByTableHeader extends Component
{
    public $sortBy;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( $sortBy )
    {
        $this->sortBy = $sortBy;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.list-builder.sort-by-table-header');
    }
}
