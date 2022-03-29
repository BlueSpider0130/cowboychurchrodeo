@extends('layouts.producer')

@section('content')
<div class="container-fluid py-4">

    <a href="{{ route('L2.entries.rodeo', [$organization->id, $rodeo->id]) }}" class="btn btn-outline-secondary btn-sm mt-n4 mb-5">
        <i class="fas fa-chevron-left pr-1"></i> 
        Rodeo
    </a>

    <x-session-alerts />

    <h1> {{ $competition->group ? $competition->group->name.' - ' : '' }} {{ $competition->event->name }} </h1>
    <hr>
    <h2 class="d-none"> Rodeo </h2>
    <div class="card mb-5">
        <div class="card-body">

            {{ $rodeo->name }} 
            <hr class="my-1">
            <div>
                {{ $rodeo->starts_at ? $rodeo->starts_at->toFormattedDateString() : 'TBA'}} 
                &ndash; 
                {{ $rodeo->ends_at ? $rodeo->ends_at->toFormattedDateString() : 'TBA' }}
            </div>

            @if( $rodeo->description )
                <p class="mt-2">{{ $rodeo->description }}</p>
            @endif

            @if( $rodeo->entry_fee )
                <table class="mt-2"> 
                    <tr>
                        <td class="pr-2"> Rodeo entry fee: </td>
                        <td> ${{ $rodeo->entry_fee ? number_format( $rodeo->entry_fee, 2) : '0.00' }} </td>
                    </tr>
                </table>
            @endif

        </div>
    </div><!--/card-->

    <h2 class="d-none"> Competition info </h2>
    <div class="card mb-5">
        <div class="card-body">
            <table class="mb-2">
                @if( $competition->group )
                    <tr> <td class="pr-2"> Group: </td> <td> {{ $competition->group->name }} </td> </tr>
                @endif
                <tr> <td class="pr-2"> Event: </td> <td> {{ $competition->event->name }} </td> </tr>
            </table>
            <div>
                @if( $competition->allow_multiple_entries_per_contestant )
                    {{ $competition->max_entries_per_contestant ? $competition->max_entries_per_contestant : 'Multiple'  }}  entries 
                @else
                    1 entry
                @endif 
                per contestant allowed.
            </div>
        </div>
    </div><!--/card-->

    <h2 class="h-reset font-weight-bold mb-2"> Contestant entries </h2>
    <table class="table bg-white border roudned table-responsive-cards">
        <thead>
            <tr>
                <th> No. </th>
                <th> Contestant </th>
                <th> Day / time </th>
                @if( $competition->event->team_roping )
                    <th class="text-md-center"> Position </th>
                    <th class="text-md-center"> Team </th>
                @endif
                <th> &nbsp; </th>
            </tr>
        </thead>

        <tbody>
            @if( $competition->entries->count() < 1 )
                <tr> <td colspan="5" class="p-5" style="font-style: italic;"> There are no entries for this event... </td> </tr>
            @endif
            @foreach( $competition->entries as $entry )
                <tr>
                    <td>#{{ $entry->id }}</td>
                    <td>{{ $entry->contestant->lexical_name_order }}</td>
                    <td> 
                        @if( $entry->instance ) 
                            <div> <x-rodeo-date :date="$entry->instance->starts_at" /> </div>
                        @else
                            <small class="text-muted"> Not assigned... </small>                           
                        @endif
                    </td>
                    @if( $competition->event->team_roping )
                        <td class="text-md-center"> 
                            @if( $entry->position )
                                <span class="badge {{ in_array($entry->position, ['header', 'heeler']) ? "badge-{$entry->position}" : ''}}">
                                    {{ $entry->position }}
                                </span>
                            @else
                                <span class="trc-label font-weight-normal"> Position: </span>
                                Any
                            @endif
                        </td>
                        <td class="text-md-center">
                            @php
                                $teamEntry = $teamRopingEntries->where('header_entry_id', $entry->id)->first();
                                
                                if( null === $teamEntry )
                                {
                                    $teamEntry = $teamRopingEntries->where('heeler_entry_id', $entry->id)->first();
                                }
                            @endphp
                            @if( $teamEntry )
                                #{{ $teamEntry->id }}
                            @endif
                        </td>
                    @endif
                    <td class="text-md-right" style="white-space: nowrap;">
                        <a href="{{ route('L2.entries.show', [$organization, $entry]) }}" class="btn btn-outline-primary btn-sm"> 
                            <i class="far fa-list-alt pr-1"></i>
                            Details 
                        </a>    
                        <a href="#" class="btn btn-outline-danger btn-sm" onclick="if( confirm('Are you sure you want to delete this entry?') ){ document.getElementById('delete-entry-{{ $entry->id }}').submit(); } else { return false; }">
                            <i class="fas fa-trash pr-1"></i>
                            Delete
                        </a>
                        <form id="delete-entry-{{ $entry->id }}" method="post" action="{{ route('L2.entries.destroy', [$organization, $entry] ) }}"> @method('delete') @csrf </form>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>

    <div>
        <a href="{{ route('L2.entries.create', [$organization, $competition]) }}" class="btn btn-outline-primary"> 
            <i class="fas fa-plus pr-1"></i>
            Add contestant
        </a>
    </div>


    @if( $competition->event->team_roping )
        <h2 class="mt-5 h-reset font-weight-bold mb-2"> Team assignments </h2>
        <hr>
        <table class="table bg-white border rounded">
            <thead>
                <tr>
                    <th> No. </th>
                    <th> Header </th>
                    <th> Heeler </th>
                    <th> Day / time </th>
                    <th> </th>
                </tr>
            </thead>
            <tbody>
                @foreach( $teamRopingEntries as $teamEntry )
                    <tr>
                        <td>{{ $teamEntry->id }}</td>
                        <td>
                            @if ($teamEntry->header_entry )                   
                                {{ $teamEntry->header_entry->contestant->lexical_name_order }}
                            @endif
                            &nbsp; - &nbsp; #{{ $teamEntry->header_entry_id }} </small>
                        </td>
                        <td>                            
                            @if ($teamEntry->heeler_entry)
                                {{ $teamEntry->heeler_entry->contestant->lexical_name_order }}
                            @endif
                            &nbsp; - &nbsp; #{{ $teamEntry->heeler_entry_id }}
                        </td>
                        <td>
                            @if( $teamEntry->instance )
                                <div> <x-rodeo-date :date="$teamEntry->instance->starts_at" /> </div>
                            @else
                                <small class="text-muted"> Not assigned... </small>
                            @endif
                        </td>
                        <td class="text-md-right" style="white-space: nowrap;">
                            {{--
                            <a href="{{ route('L2.team.entries.show', [$organization, $teamEntry]) }}" class="btn btn-outline-primary btn-sm"> 
                                <i class="far fa-list-alt pr-1"></i>
                                Details 
                            </a>
                            --}}    
                            <a href="#" class="btn btn-outline-danger btn-sm" onclick="if( confirm('Are you sure you want to delete this entry?') ){ document.getElementById('delete-team-entry-{{ $teamEntry->id }}').submit(); } else { return false; }">
                                <i class="fas fa-trash pr-1"></i>
                                Delete
                            </a>
                            <form id="delete-team-entry-{{ $teamEntry->id }}" method="post" action="{{ route('L2.team.entries.destroy', [$organization, $teamEntry] ) }}"> @method('delete') @csrf </form>
                        </td>
                    </tr>
                @endforeach        
            </tbody> 
        </table>
        <div>
            <a href="{{ route('L2.team.entries.create', [$organization, $competition]) }}" class="btn btn-primary"> 
                <i class="fas fa-plus pr-1"></i>
                Add team
            </a>
        </div>        
    @endif



</div>
@endsection
