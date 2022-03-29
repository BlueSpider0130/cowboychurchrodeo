@extends('layouts.producer')

@section('content')
<div class="container-fluid">

    <h1> Not checked in </h1>
    <hr class="mb-5">

    <h2> {{ $rodeo->name ? $rodeo->name : "Rodeo #{$rodeo->id}" }} </h2>
    <hr>
    <div class="mb-5">
        <x-rodeo-dates :model="$rodeo" />
    </div>

    <h3> {{ $group->name }} </h3>

    <table class="table bg-white">
        @foreach( $entries as $entry )
            <tr>
                <td> {{ $entry->contestant->name }} </td>
                <td> <x-rodeo-date :date="$entry->instance->starts_at" /> </td>
            </tr>
        @endforeach
    </table>

</div>
@endsection