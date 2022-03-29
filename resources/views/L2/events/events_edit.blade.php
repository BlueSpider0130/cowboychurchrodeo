@extends('layouts.producer')

@section('content')
<div class="container-fluid">

    <x-session-alerts />

    <h1> Edit event </h1>
    <hr>
    <div class="card">
        <div class="card-body">

            <form method="POST" action="{{ route('L2.events.update', [$organization->id, $event->id]) }}">
                @method('PATCH')
                @csrf

                @include('L2.events.form_fields')

                <hr>
                <x-form.buttons submit-name="Save" :cancel-url="route('L2.events.index', $organization)" />
            </form>         

        </div>
    </div><!--/card-->

</div>
@endsection
