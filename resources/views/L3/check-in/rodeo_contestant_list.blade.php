@extends('layouts.producer')

@section('content')
<div class="container-fluid" style="position: relative;">

    <x-session-alerts />

    @error('contestants')
        <div class="alert alert-danger"> {{ $message }} </div>
    @enderror
    @error('contestants.*')
        <div class="alert alert-danger"> {{ $message }} </div>
    @enderror

    <h1> {{ $rodeo->name ? $rodeo->name : 'Rodeo #'.$rodeo->id }} </h1>
    <ul class="nav nav-tabs mt-4 mb-4">
        <li class="nav-item">
            <a class="nav-link active" href="#">Check in</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('L3.check-in.checked.in', [$organization->id, $rodeo->id]) }}">Checked in</a>
        </li>
    </ul>

    @if( !$contestantsByDay )
        <span class="text-muted"> <i> There are no contestants to check in... </i> </span>
    @else
        <x-l3.check-in.contestant-select 
            :organization="$organization" 
            :rodeo="$rodeo" 
            :contestants="$contestantsByDay" 
            :check-in-entries="$checkInEntries"
        />
    @endif

  </div>
@endsection