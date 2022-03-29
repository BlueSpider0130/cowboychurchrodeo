@extends('layouts.producer')

@section('content')
<div class="container-fluid">

    <x-session-alerts />

    <h1> Rodeo: {{ $rodeo->name ? $rodeo->name : "#{$rodeo->id}" }} </h1>
    <div class="row">
        <div class="col-12 col-md-10 col-lg-8">      

            <div class="card mt-3 mb-5">
                <div class="card-body">
                    Series: {{ $series->name }}
                    <hr class="my-2">
                    <div>
                        {{ $series->starts_at ? $series->starts_at->toFormattedDateString() : 'TBA'}} 
                        &ndash; 
                        {{ $series->ends_at ? $series->ends_at->toFormattedDateString() : 'TBA' }}
                    </div>         
                </div>
            </div><!--/card-->

        </div><!--/col-->
    </div><!--/row-->


    <h2> Edit rodeo details </h2>
    <div class="row">
        <div class="col-12 col-md-10 col-lg-8">

            <div class="card">
                <div class="card-body">

                    <form method="post" action="{{ route('L2.build.series.rodeos.update', [$organization, $series, $rodeo]) }}">
                        @method('put')
                        @csrf

                        @include( 'L2.build_series.rodeo_form_fields' )                        

                        <hr class="mt-1">
                        <x-form.buttons submit-name="Update" :cancel-url="route('L2.build.series.rodeos.show', [$organization, $series, $rodeo])" />


                    </form>
                </div>
            </div><!--/card-->

        </div>
    </div><!--/row--> 

</div>
@endsection
