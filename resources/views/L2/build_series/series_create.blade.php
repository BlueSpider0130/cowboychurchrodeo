@extends('layouts.producer')

@section('content')
<div class="container-fluid">

    <x-session-alerts />

    <h1> New series </h1>
    <div class="row">
        <div class="col-12 col-md-10 col-lg-8">      

            <div class="card">
                <div class="card-body">

                    <form method="post" action="{{ route('L2.build.series.store', $organization) }}">
                        @csrf

                        @include( 'L2.build_series.series_form_fields' )

                        <hr class="mt-4">
                        <x-form.buttons submit-name="Create" :cancel-url="route('L2.build.series.index', $organization)" />

                    </form>
                </div>
            </div><!--/card-->

        </div>
    </div><!--/row--> 

</div>
@endsection
