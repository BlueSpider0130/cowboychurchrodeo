<?php

namespace App\View\Components\ListBuilder;

use Illuminate\View\Component;
use Illuminate\Http\Request;
use App\Contracts\ListBuilder;

/**
 * Create a link with the sort by and direct query parameters (and optional direction arrow).
 *
 * Example:
 *
 *      <x-list-builder.sort-by-link sort-name="created_at"> Created </x-list-builder.sort-by-link>
 *
 *      (To add additional parameters to the querystring)
 *      <x-list-builder.sort-by-link sort-name="created_at" :params="[ 'foo' => 'bar' ]"> Created </x-list-builder.sort-by-link>
 *
 *      (Use a url different than the current url)
 *      <x-list-builder.sort-by-link sort-name="created_at" href="foo"> Created </x-list-builder.sort-by-link> 
 *
 */
class SortByLink extends Component
{
    public $sortBy;    
    public $currentDirection;
    public $linkDirection;
    public $showArrows;
    public $url;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( Request $request, $sortBy=null, $href=null, $params=[], $showArrows=false )
    {
        $sortByParam = ListBuilder::SORT_BY_PARAM;
        $sortDirectionParam = ListBuilder::SORT_DIRECTION_PARAM;

        $this->sortBy = $sortBy;
        
        $this->currentDirection = null;

        if( $sortBy == $request->get( $sortByParam )  &&  $request->get( $sortDirectionParam )  &&  in_array($request->get( $sortDirectionParam ), ['asc', 'desc']) )
        {
            $this->currentDirection = $request->get( $sortDirectionParam );
        }

        $this->linkDirection = 'asc';

        if( 'asc' == $this->currentDirection )
        {
            $this->linkDirection = 'desc';
        }

        if( 'desc' == $this->currentDirection )
        {
            $this->linkDirection = null;
        }

        $this->showArrows = $showArrows;

        $queryParams = $request->query();

        if( null === $this->linkDirection )
        {
            unset($queryParams[ $sortByParam ]);
            unset($queryParams[ $sortDirectionParam ]);
        }

        if( $this->linkDirection )
        {
            $queryParams[ $sortByParam ] = $sortBy;
            $queryParams[ $sortDirectionParam ] = $this->linkDirection;
        }

        $url = $request->url();

        if( $href )
        {
            $hrefParts = parse_url($href);

            $url = url( $hrefParts['path'] );
            
            if( array_key_exists('query', $hrefParts) )
            {
                $querystringExpressions = explode('&', $hrefParts['query']);

                $hrefParams = [];

                foreach( $querystringExpressions as $expression )
                {
                    $parts = explode('=', $expression);

                    if( 2 == count($parts) )
                    {
                        $hrefParams[$parts[0]] = $parts[1];
                    }
                }

                $queryParams = array_merge($queryParams, $hrefParams);
            }
        }

        $queryParams = array_merge($queryParams, $params);

        $this->url = $url . '?' . http_build_query($queryParams);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.list-builder.sort-by-link');
    }
}
