<span>
    @php
        $count = 1;
        $size = count($links);
    @endphp
    @foreach( $links as $option => $url )
        @if( $url )
            <a href="{{ $url}}">{{ $option }}</a>
        @else
            <span class="text-muted">{{ $option }}</span>
        @endif
        {{ $count < $size  ?  '|'  :  null  }}
        @php
            $count++;
        @endphp
    @endforeach 
</span>