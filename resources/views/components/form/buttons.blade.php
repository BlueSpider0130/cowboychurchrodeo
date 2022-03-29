<div>
    <button type="submit" class="btn btn-primary"> {{ $submitName }} </button>
    
    @if( $showCancelButton )
        <a href="{{ $cancelUrl }}" class="btn btn-outline-secondary"> {{ $cancelName }} </a>
    @endif
</div>