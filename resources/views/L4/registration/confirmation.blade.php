@extends('layouts.app')

@section('content')
<div class="container">

    <div class="mb-4">
        <a href="{{ route('L4.registration.entered', $organization->id) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-chevron-left"></i>
            Registrations 
        </a>        
    </div> 


    <h1> Rodeo registration</h1>
    <hr>

    <div class="card my-3">
        <div class="card-body">

            <div>
                {{ $rodeo->name ? $rodeo->name : 'Rodeo #'.$rodeo->id }}  <br>
                <x-rodeo-date :date="$rodeo->starts_at" /> &ndash; <x-rodeo-date :date="$rodeo->ends_at" />
            </div>

            <hr class="my-4">

            <div>
                <p>
                    {{ $contestant->last_name }}, {{ $contestant->first_name }} 
                    <x-membership-badge :contestant="$contestant" series="{{ $rodeo->series_id }}" style="margin-left: .5rem;" />
                    <br>
                    {{ $contestant->birthdate ? $contestant->birthdate->toFormattedDateString() : '' }}
                </p>

                <address class="mb-0 pb-0">
                    @if($contestant->address_line_1)
                        {{ $contestant->address_line_1 }}<br>
                    @endif

                    @if($contestant->address_line_2)
                        {{ $contestant->address_line_2 }}<br>
                    @endif
                    
                    @if($contestant->city)
                        {{ $contestant->city }}, 
                    @endif
                    @if($contestant->state)
                        {{ $contestant->state }} 
                    @endif
                    @if($contestant->postcode) 
                        {{ $contestant->postcode }}
                    @endif                    
                    @if($contestant->city || $contestant->state || $contestant->postcode)
                        <br>
                    @endif
                </address>
            </div>

            <hr class="my-4">

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
    </div>

    @if( Auth::user()->contestants->count() > 1 )
        <a href="{{ route('L4.registration.contestants', [$organization, $rodeo]) }}" class="btn btn-outline-primary btn-sm"> 
            Register another contestant
        </a>
    @endif
    
</div>
@endsection

