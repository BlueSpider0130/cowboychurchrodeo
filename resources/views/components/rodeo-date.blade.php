<span {{ $attributes }}>
    @if( $date  &&  is_a( $date, \Carbon\Carbon::class ) )
        {{ $date->format('D, M d, Y') }}
    @else
        {!! isset($default) ? $default : '' !!}
    @endif
</span>
