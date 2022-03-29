@extends('layouts.producer')

@section('content')
    <h1> Reports </h1>
    <hr>
    <p class="mt-1 mb-4"> 
        <small class="text-muted"> Reports may take a while to generate depending on the number of entries, contestants, and users. </small> 
    </p>

    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('L2.reports.home', $organization->id) }}">
                Rodeo Reports
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('L2.reports.emails', $organization->id) }}">
                Emails Report
            </a>
        </li>
    </ul>

    <h2 class="mt-4">Choose Rodeo:</h2>
    <hr>
    @if($rodeos->count() > 0)
        <ul class="list-reset">
            @foreach( $rodeos as $rodeo )
                <li>
                    <a href="{{ route('L2.reports.entries.days', [$organization, $rodeo]) }}" class="text-reset d-block">
                        <div class="card mb-5">
                            <div class="card-body">
                                <h3>{{ $rodeo->name }}</h3>
                                @if($rodeo->description)
                                    <p>{{ $rodeo->description }}</p>
                                @endif
                                <hr>
                                {{ $rodeo->starts_at->toFormattedDateString() }} &ndash; {{ $rodeo->ends_at->toFormattedDateString() }}
                            </div>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    @else
        <i>No rodeos...</i>
    @endif
</div>
@endsection
