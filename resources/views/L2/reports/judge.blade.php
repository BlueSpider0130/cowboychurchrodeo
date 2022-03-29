@extends('layouts.producer')

@section('content')
    @include('L2.reports._rodeo_report_header', ['active' => 'judge'])

    <div class="text-right">
        <a href="?print=true" target="_blank" class="btn btn-primary">Print</a>
    </div>
    @foreach( $competitions->sortBy('order') as $competition )
        <div class="d-block mt-4 p-4 border border-grey rounded bg-white">
            <h2>Judge Sheets</h2>
            <h3>{{ $organization->name }}</h3>
            <h4 class="h-reset font-weight-bold">{{ $rodeo->name }}</h4>
            {{ rodeo_date_format($day) }}
            <h5 class="mt-5 font-weight-bold">{{ $competition->name }}</h5>
        </div>
        <table class="table bg-white border mb-3">
            <thead>
                <tr class="bg-light font-weight-bold">
                    <th>Draw</th>
                    <th>Contestant</th>
                    <th>City</th>
                    <th>Stock</th>
                    <th>Stock Score</th>
                    <th>Time / Score</th>
                    <th>Penalty</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $count = 0;
                @endphp
                @foreach( $entries->where('competition_id', $competition->id)->sortBy('draw') as $entry )
                    <tr>
                        <td class="border">{{ $entry->draw }}</td>
                        <td class="border">{{ $entry->contestant->lexical_name_order }}</td>
                        <td class="border">{{ $entry->contestant->city }}</td>
                        <td class="border"> </td>
                        <td class="border"> </td>
                        <td class="border"> </td>
                        <td class="border"> </td>
                        <td class="border"> </td>
                    </tr>
                    @php
                        $count++;
                    @endphp
                @endforeach
                <tr><td colspan="8" class="bg-light">Number of entries: {{ $count }}</td></tr>
                <tr><td colspan="8" class="py-4"></td></tr>
            </tbody>
        </table>
    @endforeach
@endsection
