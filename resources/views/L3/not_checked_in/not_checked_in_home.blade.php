@extends('layouts.producer')

@section('content')
<div class="container-fluid">

    <h1> Not checked in </h1>
    <hr class="mb-5">

    <h2> {{ $rodeo->name ? $rodeo->name : "Rodeo #{$rodeo->id}" }} </h2>
    <hr>
    <x-rodeo-dates :model="$rodeo" />

    @foreach( $groups as $group )
        <a 
            href="{{ route('L3.check-in.summary.not.checked.in.group', [$organization, $rodeo, $group]) }}" 
            class="d-block my-3 p-3 border rounded bg-white shadow-sm text-dark" style="text-decoration: none;"
        >
            {{ $group->name }}
        </a>
    @endforeach

</div>
@endsection