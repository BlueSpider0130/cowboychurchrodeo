@extends('layouts.app')

@section('content')
<div class="container">

    {{-- @if( Auth::user()->contestants()->count() > 1 ) --}}
        <div class="mb-4">
            <a href="{{ route('L4.registration.contestants', [$organization->id, $rodeo->id]) }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-chevron-left"></i>
                Contestants 
            </a>
        </div>
    {{--
    @else
        <div class="mb-4">
            <a href="{{ route('L4.registration.rodeos', [$organization->id, $rodeo->id]) }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-chevron-left"></i>
                Rodeos
            </a>
        </div>  
    @endif
    --}}

    <x-session-alerts />

    <h1> Rodeo registration</h1>
    <hr>
    <h2 class="font-weight-bold my-1" style="font-size: 1em;"> Rodeo info </h2>    
    @include('partials.registration.rodeo_info_card')
    
    <h2 class="font-weight-bold my-1" style="font-size: 1em;"> Contestant info </h2>
    @include('partials.registration.contestant_info_card')

    @if( $rodeo->isRegistrationClosed() )
        @if( $rodeo->hasEnded() )    
            <div class="alert alert-danger">
                Rodeo ended: <x-rodeo-date :date="$rodeo->ends_at" class="d-inline-block ml-2" /> 
            </div>
        @elseif( $rodeo->hasStarted() )
            <div class="alert alert-info">
                Registration is closed for this rodeo.
            </div>
        @elseif( $rodeo->closes_at && $rodeo->closes_at < \Carbon\Carbon::now() )
            <div class="alert alert-info">
                Registration is closed for this rodeo.
            </div>
        @elseif( $rodeo->opens_at && $rodeo->opens_at > \Carbon\Carbon::now() )
            <div class="alert alert-info">
                Registration opens: <x-rodeo-date-time :date="$rodeo->opens_at" class="d-inline-block ml-2" /> 
            </div>            
        @else
            <div class="alert alert-danger">
                Registration is closed for this rodeo.
            </div>
        @endif
    @endif

    <h2 class="font-weight-bold my-1" style="font-size: 1em;"> Events </h2>    
    <div class="card">
        
        @if( $rodeoEntry || $competitionEntries->count() > 0 )
            <div class="card-header bg-white">
                <div class="text-muted" style="font-size: .85rem">
                    @if( $rodeoEntry )
                        @if( $rodeoEntry->created_at == $rodeoEntry->updated_at ) 
                            Registered: 
                        @else
                            Last update: 
                        @endif
                        {{ $rodeoEntry->updated_at->toFormattedDateString() }}
                    @elseif( $competitionEntries->count() > 0 ) 
                        <p> Registration incomplete. </p>
                        @if( !$rodeo->hasEnded() )
                            <form method="post" action="{{ route('L4.registration.save', [$organization->id, $rodeo->id, $contestant->id]) }}">
                                @csrf()
                                <button type="submit" class="btn btn-primary btn-sm"> Complete registration </button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        @endif          


        <div class="card-body">

            @if( $competitionEntries->count() < 1 )
                <p style="font-style: italic;"> 
                    Contestant not registered for any events. 
                </p>
            @endif

            @foreach( $competitionEntries as $entry )
                <div class="mb-4">
                    <span class="font-weight-bold">
                        {{ $entry->competition->group->name }} &ndash; {{ $entry->competition->event->name }}
                    </span>
                    <hr class="my-1">
                    <x-rodeo-date :date="$entry->instance->starts_at" /><br>
                    @if( $entry->competition->event->is_team_roping )
                        Position: 
                        @if( $entry->isHeader() )
                            <x-header-badge />
                        @elseif( $entry->isHeeler() )
                            <x-heeler-badge />
                        @else
                            Any
                        @endif
                        <br>

                        @if( $entry->requested_teammate )
                            Requested teammate: {{ $entry->requested_teammate }} <br>
                        @endif
                    @endif                                
                </div>
            @endforeach

        </div>

        <div class="card-footer bg-white">

            @if( $competitionEntries->count() > 0  &&  $rodeo->isRegistrationOpen() )
                <a 
                    href="{{ route('L4.registration.entries.index', [$organization->id, $rodeo->id, $contestant->id]) }}" 
                    class="btn btn-outline-secondary btn-sm"
                > 
                    Edit entries
                </a>
            @endif

            @if( $competitionEntries->count() < 1 ) 
                @if( $rodeo->isRegistrationOpen() )
                    <a href="{{ route('L4.registration.entries.index', [$organization->id, $rodeo->id, $contestant->id]) }}" class="btn btn-primary"> 
                        Register 
                    </a>
                @elseif( $rodeo->opens_at && $rodeo->opens_at > \Carbon\Carbon::now() && !$rodeo->hasEnded() && !$rodeo->hasStarted() && (!$rodeo->closes_at || $rodeo->closes_at > \Carbon\Carbon::now()) )
                    <button class="btn btn-primary" disabled>
                        Register
                    </button>
                @endif
            @endif

        </div>
    </div>

</div>
@endsection
