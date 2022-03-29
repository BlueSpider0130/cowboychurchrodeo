<?php
    if( ! isset($perPageOptions) )
    {
        $perPageOptions = [ 15, 25, 50, 100, 'All' ];
    }

    $perPageLinks = [];

    if( is_array($perPageOptions) )
    {
        foreach( $perPageOptions as $key => $value )
        {
            if( ! is_numeric($value)  ||  $value < $results->total() )
            {
                if( $request->getPerPageCount()  &&  $value == $request->getPerPageCount() )
                {
                    $perPageLinks[] = $value;
                }
                else
                {
                    $_params = $request->getParametersForQuerystring([ 
                        $request->getPerPageCountParameterName() => $value
                    ]);

                    $_url = route( Route::currentRouteName(), $_params); 
                    
                    $perPageLinks[] = "<a href=\"{$_url}\"> {$value} </a>";
                }
            } 
        }
    }
?>

@if( count($perPageLinks) > 1 )   
    <span> 
        Results per page: <span class="ml-1"> {!! implode(" | ", $perPageLinks) !!} </span>
    </span>
@endif