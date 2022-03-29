@extends('layouts.producer')

@section('content')
    <h1> Draw </h1>
    <hr>
    <div class="mb-5">
        <h2 class="h-reset" style="font-size: 1.15rem; font-weight: bold">{{ $rodeo->name }}</h2>
        <hr class="my-1">
        {{ $rodeo->formattedStartDate() }} &ndash; {{ $rodeo->formattedEndDate() }}
    </div>

    The draw has been run.

    View the draw in the <a href="{{ route('L2.reports.draw.days', [$organization, $rodeo]) }}">reports section</a>
</div>
@endsection
