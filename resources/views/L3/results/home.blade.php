@extends('layouts.producer')

@section('content')
<div class="container-fluid">

    <h1> Record results </h1>
    <hr class="mb-5">

    <h2> Current </h2>
    <hr>
    <div class="mb-5">
        @if( $active->count() < 1 )
            <i> <small class="text-muted"> There are no active rodeos... </small> </i> 
        @endif

        @foreach( $active as $rodeo )

            <div class="my-3 p-3 border rounded bg-white shadow-sm">
                <a href="{{ route('L3.results.index', [$organization->id, $rodeo->id]) }}" class="d-block text-dark" style="text-decoration: none;">
                    <div> {{ $rodeo->name ? $rodeo->name : "Rodeo #{$rodeo->id}" }} </div>
                    <div>
                        <x-rodeo-date :date="$rodeo->starts_at" /> &ndash; <x-rodeo-date :date="$rodeo->ends_at" />
                    </div>
                </a>
            </div>

        @endforeach
    </div>

    <h2> Upcoming </h2>
    <hr>
    <div class="mb-5">
        @if( $scheduled->count() < 1 )
            <i> <small class="text-muted"> There are no upcoming rodeos.. </small> </i> 
        @endif

        @foreach( $scheduled as $rodeo )

            <div class="my-3 p-3 border rounded bg-white shadow-sm">
                <a 
                    href="{{ route('L3.results.index', [$organization->id, $rodeo->id]) }}" 
                    class="d-block text-dark" 
                    style="text-decoration: none;"
                >
                    <div> {{ $rodeo->name ? $rodeo->name : "Rodeo #{$rodeo->id}" }} </div>
                    <div>
                        <x-rodeo-date :date="$rodeo->starts_at" /> &ndash; <x-rodeo-date :date="$rodeo->ends_at" />
                    </div>
                </a>
            </div>

        @endforeach
    </div>

    <h2> Previous </h2>
    <hr>
    <div class="mb-5">
        @if( $ended->count() < 1 )
            <i> <small class="text-muted"> There are no rodeos that have ended... </small> </i> 
        @endif

        @foreach( $ended as $rodeo )

            <div class="my-3 p-3 border rounded bg-white shadow-sm">
                <a 
                    href="{{ route('L3.results.index', [$organization->id, $rodeo->id]) }}" 
                    class="d-block text-dark" 
                    style="text-decoration: none;"
                >
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