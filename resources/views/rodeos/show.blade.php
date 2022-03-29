@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $rodeo->name }}</h1>
    <hr>
    {{ $rodeo->starts_at->toFormattedDateString() }} &ndash; {{ $rodeo->ends_at->toFormattedDateString() }}

    <table class="table mt-4">
        @foreach($competitions as $competition)
            <tr>
                <td> {{ $competition->group_name }} </td>
                <td> {{ $competition->event_name }} </td>
                <td>
                    <a href="{{ route('results.show', [$organization, $competition]) }}" class="btn btn-outline-primary"> Results </a>
                </td>
            </tr>
        @endforeach
    </table>
</div>
@endsection
