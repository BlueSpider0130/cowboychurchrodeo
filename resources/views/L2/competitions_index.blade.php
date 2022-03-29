@extends('layouts.producer')

@section('content')
<div class="container-fluid py-4">

    <x-session-alerts />

    <div class="mb-4">
        <h1> Competitions </h1>
        <hr class="mt-1 mb-3 mb-md-2">
        <div class="text-md-right">
            <a href="{{ route('L2.competitions.create', [$organization]) }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-plus pr-1"></i> 
                Add new competition 
            </a>
        </div>
    </div>

    <table class="table bg-white border">
        <thead>
            <tr>
                <th style="white-space: nowrap;"> ID </th>
                <th style="white-space: nowrap;"> Series </th>
                <th style="white-space: nowrap;"> Rodeo </th>
                <th style="white-space: nowrap;"> Name </th>
                <th style="white-space: nowrap;"> Days / times </th>
                <th> </th>
            </tr>
        </thead>
        <tbody>
            @foreach( $competitions as $competition )
                <tr>
                    <td style="white-space: nowrap;">{{ $competition->id }} </td>
                    <td style="white-space: nowrap;">{{ $competition->series_id ? $competition->series->name : '' }} </td>
                    <td style="white-space: nowrap;">{{ $competition->rodeo_id ? $competition->rodeo->name : '' }}</td>
                    <td style="white-space: nowrap;">{{ $competition->name }}</td>
                    <td style="white-space: nowrap;">
                        @foreach( $competition->instances as $instance )
                            {{ $instance->date ? $instance->date->format('D, M d, Y') : 'TBA' }}
                            @if( $instance->date  &&  !$instance->anytime )
                                {{ $instance->start_time ? $instance->start_time : 'TBA' }}
                            @endif
                            <br>
                        @endforeach
                    </td>
                    <td class="text-center">
                        <a href="{{ route('L2.competitions.show', [$organization, $competition]) }}" class="btn btn-outline-primary"> Details </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
