@extends('layouts.producer')

@section('content')
<div class="mt-n4 mx-n4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"> 
                <a href="{{ route('L2.registration.rodeos.index', [$organization->id]) }}"> Rodeos </a> 
            </li>
            <li class="breadcrumb-item"> 
                <a href="{{ route('L2.registration.contestants.index', [$organization->id, $rodeo->id]) }}"> Contestants </a> 
            </li>
            <li class="breadcrumb-item active" aria-current="page"> {{ $contestant->lexical_name_order }} </li>
        </ol>
    </nav>
</div>

<div class="container-fluid">

    <x-session-alerts />

    <h1> Rodeo registration </h1>
    <hr>
    @include('partials.registration.rodeo_info_card')


    <b>Contestant</b>
    <div class="card mb-4">
        <div class="card-body">
            {{ $contestant->lexical_name_order }} <br>
            {{ $contestant->birthdate ? $contestant->birthdate->format('m/d/Y') : '' }}
        </div>        
    </div>

    <b>Events</b>
    <div class="card">
        <div class="card-body">

            @if( $competitionEntries->count() < 1 )

                <p style="font-style: italic;"> 
                    Contestant not registered for any events. 
                </p>
                
                <hr> 

                <a href="{{ route('L2.registration.entries.index', [$organization->id, $rodeo->id, $contestant->id]) }}" class="btn btn-primary"> 
                    Register 
                </a>

            @else

                <div class="text-right">
                    <div class="dropdown">
                        <a hre="#" role="button" class="text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-h fa-lg"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">                
                            <a 
                                class="dropdown-item" 
                                href="{{ route('L2.registration.entries.index', [$organization->id, $rodeo->id, $contestant->id]) }}"
                            >
                                <i class="fas fa-edit"></i>
                                Edit entries
                            </a> 

                            <a 
                                class="dropdown-item" 
                                href="#" 
                                role="button" 
                                onclick="if(confirm('Are you sure you want to delete this membership?')) { document.getElementById('delete-membership-form').submit(); } return false;"
                            >
                                <i class="fas fa-trash"></i>
                                Delete registration
                            </a>
                            <form id="delete-membership-form" method="post" action="{{ route('L2.registration.destroy', [$organization, $rodeo, $contestant]) }}">
                                @method('delete')
                                @csrf()
                            </form>
                        </div>
                    </div>
                </div> 



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

            @endif

        </div>

        @if( $competitionEntries->count() > 0 )
            <div class="card-footer bg-white">

                Check-in notes: 
                @if( $rodeoEntry  &&  $rodeoEntry->check_in_notes )
                    <p style="white-space: pre-wrap;">{{ $rodeoEntry->check_in_notes }}</p>
                @else
                    <small class="text-muted d-inline-block ml-2" ><i>none</i> </small>
                @endif
                <hr>
                <a 
                    href="{{ route('L2.registration.checkin.notes.edit', [$organization, $rodeo, $contestant]) }}" 
                    class="btn btn-outline-secondary btn-sm"
                > 
                    Edit notes 
                </a>
            </div>
        @endif
    </div>

    <hr> 
    <a href="{{ route('L2.registration.contestants.index', [$organization, $rodeo]) }}" class="btn btn-primary btn-sm"> Done </a>

</div>
@endsection