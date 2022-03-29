<?php

namespace App\View\Components\ListBuilder;

use Illuminate\View\Component;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Contracts\ListBuilder;

/**
 * Create per page links
 *
 * Example:
 *
 *      <x-list-builder.per-page-links  ... 
 * 
 */
class PerPageLinks extends Component
{
    public $links=[];

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( Request $request, LengthAwarePaginator $paginator, array $options=[25, 50, 100, 'All'] )
    {
        $perPageParam = ListBuilder::PER_PAGE_PARAM;

        $url = $request->url();

        $queryParams = $request->query();
        unset($queryParams['page']);

        foreach( $options as $option )
        {
            if( $option == $request->query($perPageParam) )
            {
                $this->links[$option] = null;
            }
            elseif( !is_numeric($option)  ||  $option <= $paginator->total() )
            {
                if( 'all' != strtolower($option)  ||  $paginator->total() > $paginator->perPage() )
                {
                    $queryParams[$perPageParam] = $option;
                    $this->links[$option] = $url . '?' . http_build_query($queryParams);
                }
            }
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.list-builder.per-page-links');
    }
}
