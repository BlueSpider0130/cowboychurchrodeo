@extends('layouts.producer')

@section('content')
<div class="container-fluid">
    <x-session-alerts />

    <h1> Series </h1>

     @if( $seriesCollection->count() < 1 )
        <hr>
        <p>
            <i>You have not built any series yet...</i>
        </p>
    @else
        <table class="table bg-white border rounded">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                @foreach( $seriesCollection as $series )
                    <tr>
                        <td>{{ $series->name }}</td>
                        <td class="text-center"> 
                            <a href="{{ route('L2.series.show', [$organization, $series]) }}" class="btn btn-outline-primary">Details</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{ { route('L2.series.create', $organization) }}" class="btn btn-primary"> 
        <i class="fas fa-plus pr-1"></i> Build new series 
    </a>

</div>
@endsection
