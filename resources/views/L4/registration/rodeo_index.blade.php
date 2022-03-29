@extends('layouts.app')

@section('content')
<div class="container">

    <div class="mb-4">
        <a href="{{ route('toolbox', $organization->id) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-chevron-left"></i>
            Toolbox
        </a>        
    </div> 

    <x-session-alerts />

    <h1> Rodeo registration </h1>

    <ul class="nav nav-tabs mt-5 mb-4">
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('L4.registration.home', [$organization->id]) }}"> 
                Registration
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('L4.registration.entered', [$organization->id]) }}"> 
                Registered
            </a>
        </li>
    </ul>

    @if( $rodeos->count() < 1 )
        <p class="my-4 text-muted"> There are no rodeos you can register for. </p>
    @endif

    @foreach( $rodeos as $rodeo )

        <div class="my-3 border rounded bg-white shadow-sm">
            <a 
                href="{{ $rodeo->isRegistrationClosed() ? '#' : route('L4.registration.contestants', [$organization->id, $rodeo->id]) }}" 
                @if( $rodeo->isRegistrationClosed() )
                    data-toggle="modal" 
                    data-target="#registration-closed-modal-{{ $rodeo->id }}"
                @endif
                class="d-block p-3 text-dark" style="text-decoration: none;"
            >

                <p class="m-0"> {{ $rodeo->name }} </p>
                <hr class="my-1">

                <p class="font-weight-bold my-1">
                    <x-rodeo-date :date="$rodeo->starts_at" /> &ndash; <x-rodeo-date :date="$rodeo->ends_at" />
                </p>

                @if( ($rodeo->opens_at && $rodeo->opens_at > \Carbon\Carbon::now())  ||  ($rodeo->closes_at && $rodeo->closes_at > \Carbon\Carbon::now()) )
                    <p class="my-0 mt-2">
                        @if( $rodeo->opens_at && $rodeo->opens_at > \Carbon\Carbon::now() )
                            Registration opens: <x-rodeo-date :date="$rodeo->opens_at" /> <br>
                        @endif
                        @if( $rodeo->closes_at && $rodeo->closes_at > \Carbon\Carbon::now() )
                            Registration closes: <x-rodeo-date :date="$rodeo->closes_at" /> <br>
                        @endif
                    </p>
                @endif

            </a>
        </div> 

        @if( $rodeo->isRegistrationClosed() )
            <!-- Modal -->
            <div class="modal fade" id="registration-closed-modal-{{ $rodeo->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title"> 
                                Registration closed 
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">

                            <div>
                                @if( $rodeo->hasEnded() )    
                                    Rodeo ended: <x-rodeo-date :date="$rodeo->ends_at" class="d-inline-block ml-2" /> 
                                @elseif( $rodeo->hasStarted() )
                                    Rodeo started: <x-rodeo-date :date="$rodeo->starts_at" class="d-inline-block ml-2" /> 
                                @elseif( $rodeo->closes_at && $rodeo->closes_at < \Carbon\Carbon::now() )
                                    Registration closed: <x-rodeo-date-time :date="$rodeo->closes_at" class="d-inline-block ml-2" /> 
                                @elseif( $rodeo->opens_at && $rodeo->opens_at > \Carbon\Carbon::now() )
                                    Registration opens: <x-rodeo-date-time :date="$rodeo->opens_at" class="d-inline-block ml-2" /> 
                                @else
                                    Registration closed
                                @endif
                            </div>

                        </div>

                    </div>
                </div>
            </div> 
        @endif
            


    @endforeach

</div>
@endsection

