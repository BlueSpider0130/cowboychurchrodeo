<div>

    @if($session->has('successAlert'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $session->get('successAlert') }}            
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($session->has('errorAlert'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $session->get('errorAlert') }}            
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($session->has('warningAlert'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ $session->get('warningAlert') }}            
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>    
    @endif

    @if( $session->has('alert')  &&  is_array($session->get('alert'))  &&  isset($session->get('alert')['message']) )
        @php 
            $alertType = isset( $session->get('alert')['type'] ) ? $session->get('alert')['type'] : null;
        @endphp
        <div class="alert alert-{{ $alertType }} alert-dismissible fade show" role="alert">
            {{ $session->get('alert')['message'] }}         
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if( $session->has('alerts')  &&  is_array($session->get('alerts')) )
        @foreach( $session->get('alerts') as $i => $alert )
            @if( isset($alert['message']) )
                @php 
                    $alertType = isset( $alert['type'] ) ? $alert['type'] : null;
                @endphp

                <div class="alert alert-{{ $alertType }} alert-dismissible fade show" role="alert">
                    {{ $alert['message'] }}   
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

            @endif
        @endforeach
    @endif

</div>