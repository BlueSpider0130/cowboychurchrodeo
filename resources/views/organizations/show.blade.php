@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $organization->name }}</h1>
    <hr>

    <p>{{ $organization->description }}</p>

    @if( $current->count() > 0 )
        @foreach( $current as $series )
            <a href="{{ route('series.show', [$organization, $series]) }}" class="text-reset">
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
        @endforeach
    @elseif( $upcoming->count() > 0 )
        <h2>Upcoming Series</h2>
        <hr>
        @foreach( $upcoming as $series )
            <a href="{{ route('series.show', [$organization, $series]) }}" class="text-reset">
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
        @endforeach
    @endif

    @if( $previous->count() > 0 )
        <h2>Previous Series</h2>
        <hr>
        @foreach( $previous as $series )
            <a href="{{ route('series.show', [$organization, $series]) }}" class="text-reset">
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
        @endforeach    
    @endif

    @if( $current->count() < 1  &&  $upcoming->count() < 1  &&  $previous->count() < 1 )
        <p class="mt-5 text-muted">
            There are no series for this organization...
        </p>
    @endif
</div>
@endsection
