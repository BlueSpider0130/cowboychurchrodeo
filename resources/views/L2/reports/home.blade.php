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

    <h2 class="mt-4">Choose Series:</h2>
    <hr>
    @if($series_collection->count() > 0)
        <ul class="list-reset">
            @foreach( $series_collection as $series )
                <li>
                    <a href="{{ route('L2.reports.rodeos', [$organization, $series]) }}" class="text-reset d-block">
                        <div class="card mb-5">
                            <div class="card-body">
                                <h3>{{ $series->name }}</h3>
                                @if($series->description)
                                    <p>{{ $series->description }}</p>
                                @endif
                                <hr>
                                {{ $series->starts_at->toFormattedDateString() }} &mdash; {{ $series->ends_at->toFormattedDateString() }}
                            </div>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    @else
        <i>There are no series yet...</i>
    @endif

</div>
@endsection
