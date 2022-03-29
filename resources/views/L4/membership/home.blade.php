@extends('layouts.app')

@section('content')
<nav aria-label="breadcrumb" style="margin: -1.5rem 0 1.5rem 0;">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('toolbox', [$organization->id]) }}">Toolbox</a></li>
        <li class="breadcrumb-item active" aria-current="page">Membership</li>
    </ol>
</nav>

<div class="container">

    <x-session-alerts />

    <h1> Membership </h1>
    <hr>
    <p class="text-muted mb-5" style="font-size: .85rem"> Membership is per rodeo series. Please select a series for membership details. </p>

    @if( $currentSeries->count() )
        <div class="mb-5">
            @foreach( $currentSeries as $series )

                <div class="my-3 border rounded bg-white shadow-sm">
                    <a href="{{ route('L4.membership.details', [$organization->id, $series->id]) }}" class="d-block text-dark p-3" style="text-decoration: none;">
                        {{ $series->name ? $series->name : "Series #{$series->id}" }}
                    </a>
                </div>      

            @endforeach
        </div>
    @endif

    @if( $previousSeries->count() )
        <h2> Series </h2>
        <hr>
        <div>
            @foreach( $previousSeries as $series )

                <div class="my-3 border rounded bg-white shadow-sm">
                    <a href="{{ route('L4.membership.details', [$organization->id, $series->id]) }}" class="d-block text-dark p-3" style="text-decoration: none;">
                        {{ $series->name ? $series->name : "Series #{$series->id}" }}
                    </a>
                </div>      

            @endforeach
        </div>
    @endif


</div>
@endsection