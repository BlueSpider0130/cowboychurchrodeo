@extends('layouts.producer')

@section('content')
    <h1> Draw </h1>
    <hr>
    <div class="mb-5">
        <h2 class="h-reset" style="font-size: 1.15rem; font-weight: bold">{{ $rodeo->name }}</h2>
        <hr class="my-1">
        {{ $rodeo->formattedStartDate() }} &ndash; {{ $rodeo->formattedEndDate() }}
    </div>

    <p>Note: The draw cannot be changed after it has been run.</p>

    <a href="{{ route('L2.draw.create', [$organization, $rodeo]) }}" class="btn btn-primary">Create Draw</a>
</div>
@endsection
