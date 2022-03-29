@if( $request->getSortable()  &&  in_array($attribute, $request->getSortable()) )

    <?php
        $linkSort      = $attribute;
        $linkDirection = 'asc';

        $sort      = $request->getSort(); 
        $direction = $request->getSortDirection();

        if( $sort == $attribute )
        {
            if( 'asc' == $direction )
            {
                $linkDirection = 'desc';
            }
            elseif( 'desc' == $direction )
            {
                $linkDirection = null;
            }
            else 
            {
                $linkDirection = 'asc';
            }
        }

        $_additionalParameters = [
            $request->getSortParameterName()          => $linkSort,
            $request->getSortDirectionParameterName() => $linkDirection
        ];

        $_params = $request->getParametersForQuerystring($_additionalParameters);

        $_route = isset($route) ? $route : Route::currentRouteName();
        $_params = isset($params) ? array_merge($params, $_params) : $_params;
        $_url = route( Route::currentRouteName(), $_params); 
    ?>

    <a href="{{ $_url }}" style="color: inherit; white-space: nowrap;"> 
        {{ $columnName }} 
        <span style="font-size: 1.1rem; margin-left: .5rem">
            @if( 'asc' == $direction && $attribute == $sort )
                &#8593;
            @elseif( 'desc' == $direction && $attribute == $sort )
                &#8595;
            @else
                &#8597;
            @endif
        </span> 
    </a>

@else
    {{ $columnName }}
@endif