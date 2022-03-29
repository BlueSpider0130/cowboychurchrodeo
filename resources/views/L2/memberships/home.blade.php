@extends('layouts.producer')

@section('content')
<div class="container-fluid py-4">

    <x-session-alerts />

    <h1> Membership </h1>
    <hr>
    <p class="text-muted mb-5" style="font-size: .85rem"> 
        Membership is per rodeo series.
    </p>


    @if( $currentSeries->count() )
        <h2> Current series </h2>  
        @include( 'L2.memberships.partials.series_table', ['seriesCollection' => $currentSeries])
    @endif 

    @if( $currentSeries->count() < 1  ||  $allSeries->count() > $currentSeries->count() )

        <h2> All series </h2>  
        @if( $allSeries->count() < 1 )
            <hr>
            <i> You have not created any series yet... </i>
        @else
            @include( 'L2.memberships.partials.series_table', ['seriesCollection' => $allSeries])
        @endif

    @endif

</div>
@endsection
