@extends('layouts.producer')

@section('content')
<div class="container-fluid">

    <x-session-alerts />

    <h1> Rodeo registration</h1>
    <hr>
    @include('partials.registration.rodeo_info_card')

    <h2 class="font-weight-bold my-1" style="font-size: 1em;"> Contestant info </h2>
    @include('partials.registration.contestant_info_card')

    @if( !$rodeoEntry  &&  $competitionEntries->count() > 0 )
        <div class="alert alert-info"> The registration will not be complete until you press the done button. </div>
    @endif

    <h2 class="font-weight-bold my-1 mt-5" style="font-size: 1em;"> Events </h2>    
    <hr>
    @foreach( $sortedCompetitions as $id => $set )
        <div class="card mb-3">
            <div class="card-header bg-white">
                <a 
                    class="d-block text-dark"
                    style="text-decoration: none" 
                    data-toggle="collapse" 
                    href="#event-set-{{ $id }}" 
                    role="button" 
                    aria-expanded="false" 
                    aria-controls="event-set-{{ $id }}"                    
                >
                    <h3 class="h-reset font-weight-bold" style="font-size: 1.1rem"> {{ $set->group->name }} </h3>
                </a>
            </div>
            @php
                $collapse =  $competitionEntries->where('competition.group_id', $set->group->id)->count() > 0  ?  false  :  true; 
            @endphp
            <div class="card-body @if($collapse) collapse @endif" id="event-set-{{ $id }}">
                @foreach( $set->competitions as $competition )
                    <div class="mb-4">

                        <h4 class="h-reset font-weight-bold"> 
                            {{ $set->group->name }} 
                            &nbsp;&ndash;&nbsp; 
                            {{ $competition->event->name }} 
                        </h4>
                        <hr class="my-1">
    
                        <p class="my-1"> ${{ $competition->entry_fee ? $competition->entry_fee : '0.00' }} </p>

                        @foreach( $competitionEntries->where('competition_id', $competition->id) as $entry )

                            <div class="border rounded p-2 mb-3">
                                <form 
                                    class="d-inline" 
                                    method="post" 
                                    action="{{ route('L2.registration.entries.destroy', [$organization->id, $entry->id]) }}"
                                >
                                    @method('delete') 
                                    @csrf() 
                                    <button type="submit" class="btn-reset" style="float: right" onclick="return confirm('Are sure you want to delete this entry?')"> 
                                        <i class="far fa-times-circle fa-lg text-danger"></i>
                                    </button>
                                </form>

                                @if($entry->instance)
                                    <x-rodeo-date :date="$entry->instance->starts_at" /> <br>
                                @else
                                    <span class="text-muted" style="font-size: .85rem; font-style: italic;"> Day not selected...</span>
                                @endif

                                @if( $competition->event->is_team_roping )
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

                                <hr class="my-2">
                                <a href="{{ route('L2.registration.entries.edit', [$organization->id, $entry->id]) }}" class="btn btn-outline-secondary btn-sm"> 
                                    Change
                                </a>             
                            </div>

                        @endforeach

                        
                        @php
                            $entered = $competitionEntries->where('competition_id', $competition->id)->count();
                        @endphp
                        @if( $entered < 1  ||   null === $competition->max_entries_per_contestant  ||  $entered < $competition->max_entries_per_contestant )
                            @if( 1 !== $competition->max_entries_per_contestant )
                                <p class="text-secondary my-1" style="font-size: .85rem; font-style: italic;">
                                    {{ $competition->max_entries_per_contestant > 1  ?  $competition->max_entries_per_contestant  :  'Multiple' }} entries allowed. 
                                </p>
                            @endif
                            <div>
                                <a href="{{ route('L2.registration.entries.create', [$organization->id, $rodeo->id, $contestant->id, $competition->id]) }}" class="btn btn-outline-primary btn-sm">
                                    Register
                                </a>
                            </div>
                        @endif    

                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <hr>
    <form method="post" action="{{ route('L2.registration.save', [$organization->id, $rodeo->id, $contestant->id]) }}">
        @csrf()
        <button class="btn btn-primary"> Done </button>
    </form>
    <br>
    <br>
    <br>
</div>
@endsection

