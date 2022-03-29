@extends('layouts.app')

@section('content')
<div class="container">

    <div class="mb-4">
        <a href="{{ route('L4.registration.home', [$organization->id]) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-chevron-left"></i>
            Rodeos
        </a>
    </div>  

    <x-session-alerts />

    <h1> Rodeo registration</h1>
    <hr>
    @include('partials.registration.rodeo_info_card')

    @if( $contestants->count() < 1 )
        <div class="mt-3">
            <p> You need to add a contestant account before you can register for rodeo events. </p>
            <a href="{{ route('L4.contestants.index', [$organization->id]) }}" class="btn btn btn-primary">
                <i class="fas fa-plus pr-1"></i> 
                Add contestant 
            </a>
        </div>
    @else
        <h2 style="font-weight: bold; font-size: 1rem;" class="mt-5 mb-0 py-0"> Select contestant to register </h2>
        <hr class="my-2">
        @foreach( $contestants as $contestant )
            <div class="my-3 border rounded bg-white shadow-sm">
                <a href="{{ route('L4.registration.show', [$organization->id, $rodeo->id, $contestant->id]) }}" class="d-block text-dark p-3" style="text-decoration: none;">
                    {{ $contestant->name }}
                </a>
            </div>        
        @endforeach
    @endif

</div>
@endsection

