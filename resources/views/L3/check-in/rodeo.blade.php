@extends('layouts.producer')

@section('content')
<div class="container-fluid">

    <h1> Rodeo check-in</h1>
    <hr>

    <h2 class="d-none"> Rodeo details </h2>
    <div class="card mb-4">
        <div class="card-body">
            <strong style="font-weight: bold;"> {{ $rodeo->name ? $rodeo->name : "Rodeo #{$rodeo->id}" }} </strong> <br> 
            <x-rodeo-date :date="$rodeo->starts_at" /> &ndash; <x-rodeo-date :date="$rodeo->ends_at" /> <br>
            @if( $rodeo->entry_fee )
                Entry fee: {{ $rodeo->entry_fee }} <br>
            @endif
            @if( $rodeo->opens_at  &&  $rodeo->opens_at > \Carbon\Carbon::now() )
                Registration opens: &nbsp; {{ $rodeo->opens_at->toDayDateTimeString() }} <br>
            @elseif( $rodeo->closes_at  &&  $rodeo->closes_at > \Carbon\Carbon::now() )
                Registration closes: &nbsp; {{ $rodeo->closes_at->toDayDateTimeString() }} <br>
            @elseif( $rodeo->starts_at  &&  $rodeo->starts_at > \Carbon\Carbon::now() )
                Rodeo starts: &nbsp; {{ $rodeo->starts_at->toDayDateTimeString() }} <br>
            @else
                @if( $rodeo->starts_at  &&  $rodeo->starts_at <= \Carbon\Carbon::now() )
                    Rodeo started: &nbsp; {{ $rodeo->starts_at->toDayDateTimeString() }} <br>
                @endif
            @endif    

            <p class="mt-4">
                {{ $rodeoEntryCount }} contestant{{ 1 == $rodeoEntryCount ? 's' : '' }} registered.
            </p>
            <p>
                {{ $checkedInCount }} contestant{{ 1 == $checkedInCount ? 's' : '' }} contestants checked in.
            </p>
            <p>
                {{ $notCheckedInCount }} contestant{{ 1 == $notCheckedInCount ? 's' : '' }} NOT checked in. 
                
                <a href="{{ route('L3.check-in.summary.not.checked.in', [$organization, $rodeo]) }}" class="btn btn-outline-primary btn-sm ml-2"> 
                    View 
                </a>
                
            </p> 

        </div><!--/card-body-->
    </div>

    <h2 class="h-reset font-weight-bold"> Check-in </h2>
    <div class="card"> 
        <div class="card-body">

            @if( $rodeo->ends_at  &&  $rodeo->ends_at->copy()->startOfDay()->addDays(1)->subSeconds(1) < \Carbon\Carbon::now() )
                <p class="text-danger font-weight-bold"> 
                    Rodeo ended &nbsp;
                    <x-rodeo-date :date="$rodeo->ends_at" /> 
                </p>
            @elseif( $rodeo->starts_at  )
                <p>
                    Rodeo starts: &nbsp;
                    <x-rodeo-date :date="$rodeo->starts_at" />
                </p>
            @endif

            <a href="{{ route('L3.check-in.contestants', [$organization->id, $rodeo->id]) }}" class="btn btn-primary" > Work check-in </a>

        </div>
    </div>

</div>
@endsection