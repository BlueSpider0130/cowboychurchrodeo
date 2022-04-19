@extends('layouts.producer')

@section('content')
<div class="container-fluid">

    <h1> Rodeo check-in </h1>
    <hr class="mb-5">

    <h2> Current </h2>
    <hr>
    <div class="mb-5">
        @if( $inProgress->count() < 1 )
            <i> <small class="text-muted"> There are no active rodeos... </small> </i> 
        @endif

        @foreach( $inProgress as $rodeo )

            <div class="my-3 p-3 border rounded bg-white shadow-sm">
                <a href="{{ route('L3.check-in.rodeo', [$organization->id, $rodeo->id]) }}" class="d-block text-dark" style="text-decoration: none;">
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
            <i> <small class="text-muted"> There are no scheduled rodeos... </small> </i> 
        @endif

        @foreach( $scheduled as $rodeo )

            @php
                $days = \Carbon\Carbon::now()->diffInDays( $rodeo->starts_at );
            @endphp
            <div class="my-3 p-3 border rounded bg-white shadow-sm">
                <!-- <a 
                    href="{{ route('L3.check-in.rodeo', [$organization->id, $rodeo->id]) }}" 
                    class="d-block text-dark" 
                    style="text-decoration: none;"
                    onclick="return confirm('This rodeo starts in {{ $days }} day{{ $days != 1 ? 's' : '' }}. Are you sure you want to check in contestants?')"
                > -->
                <a 
                    href="{{ route('L3.check-in.rodeo', [$organization->id, $rodeo->id]) }}" 
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
                <!-- <a 
                    href="{{ route('L3.check-in.rodeo', [$organization->id, $rodeo->id]) }}" 
                    class="d-block text-dark" 
                    style="text-decoration: none;"
                    onclick="return confirm('This rodeo has ended. Are you sure you want to check in contestants?')"
                > -->
                <a 
                    href="{{ route('L3.check-in.rodeo', [$organization->id, $rodeo->id]) }}" 
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