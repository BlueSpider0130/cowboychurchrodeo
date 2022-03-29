<div>
    <form 
        id="{{ $formId }}"
        method="{{ 'GET' == strtoupper($method) ? 'GET' : 'POST' }}" 
        action="{{ $action }}" 
    >
        @if( 'GET' != strtoupper($method) )
            @csrf        
            @if( 'POST' != strtoupper($method) )  
                @method( $method )  
            @endif 
        @endif

        @foreach($params as $paramName => $paramValue )
            <input type="hidden" name="{{ $paramName }}" value="{{ $paramValue }}" />
        @endforeach 

        @if( $showSearchButton )
            <div class="input-group">
        @endif

                <input 
                    type="text" 
                    class="form-control" 
                    name="{{ $name }}" 
                    @if( isset($value) ) value="{{ $value }}" @endif
                    @if( isset($placeholder) ) placeholder="{{ $placeholder }}" @endif                
                    @if( isset($placeholder) ) aria-label="{{ $placeholder }}" @endif
                    aria-describedby="search-form-button-{{ $key }}"
                >
                @if( $showSearchButton )
                    <div class="input-group-append">
                        <button 
                            class="btn btn-outline-secondary" 
                            type="button" 
                            id="search-form-button-{{ $key }}" 
                            onclick="getElementById('{{ $formId }}').submit();"
                        > 
                            <i class="fas fa-search"></i> 
                        </button>
                    </div>
                @endif

        @if( $showSearchButton )
            </div>
        @endif

    </form>
</div>