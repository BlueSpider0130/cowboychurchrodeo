@extends('layouts.producer')

@section('content')
<div class="container-fluid">
    <x-session-alerts />

    <h1> Rodeos </h1>

     @if( $rodeos->count() < 1 )
        <hr>
        <p>
            <i>You have not built any rodeos yet...</i>
        </p>
    @else
        <table class="table bg-white border rounded">
            <thead>
                <tr>
                    <th>Name</th>
                    <th> Series </th>
                    <th> </th>
                </tr>
            </thead>
            <tbody>
                @php
                    $rodeos->load('series');
                @endphp
                @foreach( $rodeos as $rodeo )
                    <tr>
                        <td>{{ $rodeo->name }}</td>
                        <td>{{ $rodeo->series_id ? $rodeo->series->name : '' }}</td>
                        <td class="text-center"> 
                            <a href="{{ route('L2.rodeos.show', [$organization, $rodeo]) }}" class="btn btn-outline-primary">Details</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{ { route('L2.rodeos.create', $organization) }}" class="btn btn-primary"> 
        <i class="fas fa-plus pr-1"></i> Add new rodeos 
    </a>

</div>
@endsection
