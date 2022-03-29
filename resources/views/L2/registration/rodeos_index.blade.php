@extends('layouts.producer')

@section('content')
<div class="container-fluid">

    <h1> Rodeo registration </h1>
    <hr>

    @if( $rodeos->count() < 1 )
        <div class="mb-5">
            <small class="text-muted"><i> All rodeos have ended. </i></small>
        </div>
    @else
        <h2 class="d-none"> Rodeos </h2>
        <div class="my-5">
            @include('L2.registration.partials.rodeo_table', ['rodeos' => $rodeos])
        </div>
    @endif

    <h2> Previous rodeos </h2>
    <hr>
    <div class="mb-5">
        @include('L2.registration.partials.rodeo_table', ['rodeos' => $endedRodeos])
    </div>

</div>
@endsection
