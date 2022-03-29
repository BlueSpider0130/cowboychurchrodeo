@extends('layouts.producer')

@section('content')
<div class="container-fluid">

    <x-session-alerts />

    <h1> {{ $group->name }} &ndash; {{ $event->name }} </h1>
    <hr>
    <div class="text-right">
        <div class="dropdown">
            <a hre="#" role="button" class="text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-ellipsis-h fa-lg"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                
                <a class="dropdown-item" href="{{ route('L2.build.series.rodeos.show', [$organization, $series, $rodeo]) }}">
                    Return to rodeo 
                </a>
 
                <button class="dropdown-item" type="button" onclick="if( confirm('Are you sure you want to remove this event from the rodeo?') ) { document.getElementById('series-delete-form').submit(); }">
                    <span class="text-danger">Remove from rodeo</span>
                </button>
                <form method="post" action="{{ route('L2.build.series.competitions.destroy', [$organization, $competition]) }}" class="d-none" id="series-delete-form"> @method('delete') @csrf </form>
            </div>
        </div>
    </div>



    <h2 class="h-reset font-weight-bold my-0 mb-1"> Series </h2>
    <div class="row mb-4">
        <div class="col-12 col-md-10 col-lg-8">      

            <div class="card">
                <div class="card-body">
                    
                    {{ $series->name }}
                    <hr class="my-2">
                    <div>
                        {{ $series->starts_at ? $series->starts_at->toFormattedDateString() : 'TBA'}} 
                        &ndash; 
                        {{ $series->ends_at ? $series->ends_at->toFormattedDateString() : 'TBA' }}
                    </div>

                </div><!--/card-body-->
            </div><!--/card-->

        </div><!--/col-->
    </div><!--/row-->

    <h3 class="h-reset font-weight-bold my-0 mb-1"> Rodeo </h3>
    <div class="row mb-5">
        <div class="col-12 col-md-10 col-lg-8">

                <div class="card">
                    <div class="card-body">

                        {{ $rodeo->name }} 
                        <hr class="my-1">
                        <div>
                            {{ $rodeo->starts_at ? $rodeo->starts_at->toFormattedDateString() : 'TBA'}} 
                            &ndash; 
                            {{ $rodeo->ends_at ? $rodeo->ends_at->toFormattedDateString() : 'TBA' }}
                        </div>

                        @if( $rodeo->description )
                            <p class="mt-2">{{ $rodeo->description }}</p>
                        @endif

                        @if( $rodeo->entry_fee )
                            <table class="mt-2"> 
                                <tr>
                                    <td class="pr-2"> Rodeo entry fee: </td>
                                    <td> ${{ $rodeo->entry_fee ? number_format( $rodeo->entry_fee, 2) : '0.00' }} </td>
                                </tr>
                            </table>
                        @endif

                    </div>
                </div><!--/card-->

        </div>
    </div><!--/row--> 

    <h4 class="h-reset font-weight-bold my-0 mb-1"> Event details </h4>
    <div class="row mb-5">
        <div class="col-12 col-md-10 col-lg-8">

                <div class="card">
                    <div class="card-body">

                        <form method="post" action="{{ route('L2.build.series.competitions.update', [$organization, $competition]) }}">
                            @method('put')
                            @csrf

                            @include( 'L2.build_series.partials.competition_form_fields' )

                            <hr class="mt-1">
                            <x-form.buttons submit-name="Update" :cancel-url="route('L2.build.series.rodeos.show', [$organization, $series, $rodeo])" />

                        </form>

                    </div><!--/card-body-->
                </div><!--/card-->

        </div><!--/col-->
    </div><!--/row-->
</div>
@endsection
