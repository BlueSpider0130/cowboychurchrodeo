@extends('layouts.app')

@section('content')
<nav aria-label="breadcrumb" style="margin: -1.5rem 0 1.5rem 0;">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('toolbox', [$organization->id]) }}">Toolbox</a></li>
        <li class="breadcrumb-item"><a href="{{ route('L4.membership.home', $organization->id) }}">Membership</a></li>
    </ol>
</nav>

<div class="container">

    <x-session-alerts />

    <h1> {{ $series->name ? $series->name : "Series #{$series->id}" }} </h1>
    <hr>
    <p class="mb-5">
        Membership fee: ${{ number_format($series->membership_fee, 2) }} <br>
    </p>

    <div class="card my-3">
        <div class="card-header bg-white">
            <b>
                {{ $contestant->name }}
                <x-membership-badge :contestant="$contestant" :series="$series" />
            </b>
        </div>

        <div class="card-body">

            @if( $series->starts_at  &&  $series->ends_at )
                <p>
                    Membership for series {{ $series->starts_at->toFormattedDateString() }} &ndash; {{ $series->ends_at->toFormattedDateString() }}
                </p>
            @endif
            
            <form method="post" action="{{ route('L4.membership.store', [$organization->id, $series->id, $contestant->id]) }}">
                @csrf()

                <button type="submit" class="btn btn-primary"> Register for membership </button>
            </form>

        </div>
    </div>

</div>
@endsection