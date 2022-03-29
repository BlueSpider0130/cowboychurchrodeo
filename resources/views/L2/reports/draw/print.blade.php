@extends('layouts.print')

@section('content')
    <div class="d-block mt-4 p-4 border border-grey rounded bg-white">
        <h2>Draw Sheet</h2>
        <h3>{{ $organization->name }}</h3>
        <h4 calass="h-reset font-weight-bold">{{ $rodeo->name }}</h4>
        {{ rodeo_date_format($day) }}
    </div>
    <table class="table bg-white border mb-3">
        <tbody>
            @foreach( $competitions as $competition )
                <tr><td colspan="3" class="py-3 bg-secondary text-light">{{ $competition->name }}</td></tr>
                <tr class="bg-light font-weight-bold">
                    <td></td>
                    <td>Contestant</td>
                    <td>City</td>
                </tr>
                @php
                    $count = 0;
                @endphp
                @foreach( $entries->where('competition_id', $competition->id)->sortBy('draw') as $entry )
                    <tr>
                        <td>{{ $entry->draw }}</td>
                        <td>{{ $entry->contestant->lexical_name_order }}</td>
                        <td>{{ $entry->contestant->city }}</td>
                    </tr>
                    @php
                        $count++;
                    @endphp
                @endforeach
                <tr><td colspan="3" class="bg-light">Number of entries: {{ $count }}</td></tr>
                <tr><td colspan="3" class="py-4"></td></tr>
            @endforeach
        </tbody>
    </table>
@endsection
