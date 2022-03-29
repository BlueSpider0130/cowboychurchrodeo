@extends('layouts.app')

@section('content')
<div class="container">
<h1>{{ $competition->name }}</h1>
    <hr>
    {{-- $competition->starts_at->toFormattedDateString() }} &ndash; {{ $competition->ends_at->toFormattedDateString() --}}

    <table class="table bg-white mt-4">
        @foreach($results as $entry)
            <tr>
                <td>{{ $entry->contestant->lexical_name_order }}</td>
                <td>{{ $entry->score }}</td>
            </tr>
        @endforeach
        @foreach($pending->sortBy('contestant.last_name') as $entry)
            <tr>
                <td>{{ $entry->contestant->lexical_name_order }}</td>
                <td>
                    @if(in_array($entry->contestant_id, $checkedInIds))
                        <i class="secondary">pending</i>
                    @else
                        <span class="text-secondary"> ---- </span>
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
</div>
@endsection
