@extends('layouts.producer')

@section('content')
<div class="container-fluid">

    <x-session-alerts />

    <h1> Add rodeo to series </h1>
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


    <h2> New rodeo </h2>
    <div class="row">
        <div class="col-12 col-md-10 col-lg-8">

            <div class="card">
                <div class="card-body">

                    <form method="post" action="{{ route('L2.build.series.rodeos.store', [$organization, $series]) }}">
                        @csrf

                        @include( 'L2.build_series.rodeo_form_fields' )

                        <hr class="mt-1">
                        <x-form.buttons submit-name="Create" :cancel-url="route('L2.build.series.show', [$organization, $series])" />


                    </form>
                </div>
            </div><!--/card-->

        </div>
    </div><!--/row--> 

</div>
@endsection
