@extends('layouts.producer')

@section('content')
<div class="container-fluid">
    <x-session-alerts />

    <h1> Events </h1>

     @if( $events->count() < 1 )
        <hr>
        <p>
            <i>No events created yet...</i>
        </p>
    @else
        <table class="table bg-white border rounded">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th class="text-center">Team roping</th>
                    <th class="text-center">Result type</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                @foreach( $events as $event )
                    <tr>
                        <td style="white-space: nowrap;">{{ $event->name }}</td>
                        <td>{{ $event->description }}</td>
                        <td class="text-center">
                            @if( $event->team_roping )
                                <i class="fas fa-check"></i>
                            @endif
                        </td>
                        <td class="text-center">
                            @if( in_array($event->result_type, ['score', 'time']) )
                                {{ ucfirst($event->result_type) }}
                            @endif
                        </td>
                        <td class="text-center"> 
                            <a href="{{ route('L2.events.edit', [$organization->id, $event->id]) }}" class="text-secondary" title="Edit">
                                <i class="fas fa-edit fa-lg mx-2"></i>
                            </a>

                            <x-delete-button 
                                url="{{ route('L2.events.destroy', [$organization->id, $event->id]) }}" 
                                message="Are you sure you want to delete this event?"
                            >
                                <i class="fas fa-trash fa-lg text-danger"></i>
                            </x-delete-button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ route('L2.events.create', $organization->id) }}" class="btn btn-primary"> 
        <i class="fas fa-plus pr-1"></i> Add new event 
    </a>

</div>
@endsection
