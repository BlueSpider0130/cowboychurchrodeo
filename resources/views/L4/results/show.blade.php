@extends('layouts.app')

@section('content')
<div class="container">

    <div class="mb-4">
        <a href="{{ route('L4.results.index', [$organization->id, $rodeo->id]) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-chevron-left"></i>
            Rodeo events
        </a>        
    </div> 

    <h1> Rodeo results </h1>
    <hr class="mb-4">

    <div class="card mb-5">
        <div class="card-body">
            {{ $rodeo->name ? $rodeo->name : "Rodeo #{$rodeo->id}" }} <br>
            <x-rodeo-dates :model="$rodeo" />
        </div>
    </div>

    <h2> {{ $competition->group->name }} &ndash; {{ $competition->event->name }} </h2>
    <hr>
    <table class="table table-responsive-cards bg-white border">
        <thead>
            <tr>
                <th> Entry </th>
                <th> Contestant </th>
                <th> Score </th>
            </tr>
        </thead>
        <tbody>
            @foreach( $entries as $entry )
                @php
                    $ownContestant = in_array($entry->contestant_id, $ownContestantIds);
                @endphp
                <tr class="@if($ownContestant) table-success @endif">
                    <td> 
                        <span class="d-md-none"> Entry: </span>
                        #{{ $entry->id }} 
                    </td>
                    <td> 
                        {{ $entry->contestant->lexical_name_order }} 
                    </td>
                    <td> 
                        <span class="d-md-none"> Score: </span> 
                        @if( null !== $entry->score )
                            {{ $entry->score }} 
                        @else
                            <small class="text-muted"> <i>No score reported</i> </small>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
