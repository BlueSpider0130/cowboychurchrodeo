@extends('layouts.producer')

@section('content')
<div class="container-fluid">

    <x-session-alerts />

    <h1> Add new event </h1>
    <hr>
    <div class="card">
        <div class="card-body">

            <form method="POST" action="{{ route('L2.events.store', $organization) }}">
                @csrf

                @include('L2.events.form_fields')

                <hr>
                <x-form.buttons submit-name="Add" :cancel-url="route('L2.events.index', $organization)" />
            </form>         

        </div>
    </div><!--/card-->

</div>
@endsection
