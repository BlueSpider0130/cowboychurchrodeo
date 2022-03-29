@extends('layouts.producer')

@section('content')
<div class="container-fluid py-4">

    <x-session-alerts />

    <div class="mb-4">
        <h1> Entries </h1>
        <hr class="mt-1 mb-3 mb-md-2">
        <div class="text-md-right">
            <a href="{ { route('L2.competitions.create', [$organization]) }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-plus pr-1"></i> 
                Add new competition 
            </a>
        </div>
    </div>

    <table class="table bg-white border">
        <thead>
            <tr>
                <th style="white-space: nowrap;"> Id </th>
                <th style="white-space: nowrap;"> Contestant </th>
                <th style="white-space: nowrap;"> Competition </th>
                <th style="white-space: nowrap;"> Instance </th>
                <th style="white-space: nowrap;"> Days / times </th>
                <th> </th>
            </tr>
        </thead>
        <tbody>
            @foreach( $entries as $entry )
                <tr>
                    <td style="white-space: nowrap;">{{ $entry->id }} </td>
                    <td style="white-space: nowrap;">{{ $entry->contestant->name }}</td>
                    <td style="white-space: nowrap;">{{ $entry->competition->name }}</td>
                    <td style="white-space: nowrap;">{{-- $entry->competition_instance->name --}}</td>
                    <td style="white-space: nowrap;">{{-- $entry->competition_instance->starts_at ? $entry->competition_instance->starts_at->toDayDateTimeString() : 'TBA' --}} </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
