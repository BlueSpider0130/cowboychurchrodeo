@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $series->name }}</h1>
    <hr>
    <p>{{ $series->description }}</p>

    <div class="mt-4">
        <h2>Rodeos</h2>
        <hr>
        @foreach( $series->rodeos()->orderBy('starts_at')->get() as $rodeo )
            <a href="{{ route('rodeos.show', [$organization, $rodeo]) }}" class="text-reset">
                <div class="card mb-5">
                    <div class="card-body">
                        <h3>{{ $rodeo->name }}</h3>
                        {{ $rodeo->starts_at->toFormattedDateString() }} &dash; {{ $rodeo->ends_at->toFormattedDateString() }}
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <div class="mt-4">
        <h2>Documents</h2>
        <hr>
        <ul class="no-bullets">
            @foreach($series->documents as $document)
                <li>
                    <a href="{{ route('documents.download', $document) }}" class="text-reset d-block">
                        <div class="d-flex my-1 py-0">
                            <div class="d-flex-shrink my-0 mr-3">
                                <i class="far fa-file-alt fa-2x"></i>
                            </div>
                            <div class="my-0 mx-0">
                                {{ $document->name }}
                                @if($document->description)
                                    <p>{{ $document->description }}</p>
                                @endif
                            </div>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
