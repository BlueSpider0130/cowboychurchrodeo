@extends('layouts.producer')

@section('content')
<div class="container-fluid">

    <x-session-alerts />

    <h1> Edit series </h1>
    <div class="row">
        <div class="col-12 col-md-10 col-lg-8">

            <div class="card">
                <div class="card-body">

                    <form method="post" action="{{ route('L2.build.series.update', [$organization, $series]) }}">
                        @method('put')
                        @csrf

                        @include( 'L2.build_series.series_form_fields', ['series' => $series] )                        

                        <hr class="mt-4">
                        <x-form.buttons submit-name="Update" :cancel-url="route('L2.build.series.show', [$organization, $series])" />


                    </form>
                </div>
            </div><!--/card-->

        </div>
    </div><!--/row--> 

</div>
@endsection
