<a href="{{ $url }}" {{ $attributes }}>
    @if( $slot->toHtml() )
        {{ $slot }}
    @else
        {{ ucfirst(str_replace('_', ' ', $sortBy) ) }}
    @endif
    @if( $showArrows )
        <span style="font-size: 1.1rem; margin-left: 0.5rem;">
            @if( 'asc' == $currentDirection )
                &#x2191;
            @elseif( 'desc' == $currentDirection )
                &#x2193;
            @else
                &#x2195;
            @endif 
        </span>
    @endif
</a>