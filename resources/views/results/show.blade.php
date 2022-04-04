@extends('layouts.app')

@section('content')
<div class="container">
<h1>{{ $competition->name }}</h1>
    <hr>
    {{-- $competition->starts_at->toFormattedDateString() }} &ndash; {{ $competition->ends_at->toFormattedDateString() --}}
    <!-- {{json_encode($checkedInIds)}} -->
    <table class="table bg-white mt-4">
        <tr>
            <td>name</td>
            <td>Paid</td>
            <td>score</td>
        </tr>
        @foreach($results->sortBy('score') as $entry)
            <tr>
                <td>{{ $entry->contestant->lexical_name_order }}</td>
                <td>
                @foreach($checkedInIds as $paidContestantIds)
                    @if($entry->contestant->id == $paidContestantIds)
                        <i class="fas fa-check"></i>
                    @endif
                @endforeach
                </td>
                <td>{{ $entry->score }}</td>
            </tr>
        @endforeach
        @foreach($pending->sortBy('contestant.last_name') as $entry)
            <tr>
                <td>{{ $entry->contestant->lexical_name_order }}</td>
                <td>
                    @if($entry->payment_id)
                    <i class="fas fa-check"></i>
                    @endif
                </td>
                <td>
                    @if(in_array($entry->contestant_id, $checkedInIds))
                        <i class="secondary">pending</i>
                    @else
                        <span class="text-secondary"> ---- </span>
                    @endif
                </td>
                <!-- <td>{{$entry}}</td> -->
            </tr>
        @endforeach
    </table>
</div>
@endsection
