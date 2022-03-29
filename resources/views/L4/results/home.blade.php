@extends('layouts.app')

@section('content')
<div class="container">

    <h1> Rodeo results </h1>
    <hr class="mb-4">

    <div class="mb-5">
        @if( $rodeos->count() < 1 )
            <i> <small class="text-muted"> There are no rodeos with results...</small> </i> 
        @endif

        @foreach( $rodeos as $rodeo )

            <div class="my-3 p-3 border rounded bg-white shadow-sm">
                <a href="{{ route('results.show', [$organization->id, $rodeo->id]) }}" class="d-block text-dark" style="text-decoration: none;">
                    <div> {{ $rodeo->name ? $rodeo->name : "Rodeo #{$rodeo->id}" }} </div>
                    <div>
                        <x-rodeo-date :date="$rodeo->starts_at" /> &ndash; <x-rodeo-date :date="$rodeo->ends_at" />
                    </div>
                </a>
            </div>

        @endforeach
    </div>

</div>
@endsection