@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    {{ $rodeo->name }} - Rodeo #{{ $rodeo->id }}
    <hr>
    {{ $day ? $day->toDateTimeString() : '' }}

    @if( !$results )
        Nothing to see... no results here...
    @else
        <table class="table">
            @foreach($results as $row)
                <tr>
                    <td>
                        <i>{{ $row->entry->line }}</i>
                        <div style="font-size: .65rem; padding: 0 1rem;">
                            Name: {{ $row->entry->name }} <br>
                            Group: {{ $row->entry->group }} <br>
                            Event: {{ $row->entry->event }} <br>
                        </div>
                    </td>
                    <td>
                        @if( 'success' == $row->status )
                            <span class="text-success"> Success </span>
                        @elseif( 'error' == $row->status )
                            <span class="text-danger"> Error </span>
                        @else
                            {{ $row->status ? $row->status : '' }}
                        @endif
                    </td>
                    <td>{{ $row->message }}</td>
                </tr>
            @endforeach
        </table>
    @endif

</div>
@endsection
