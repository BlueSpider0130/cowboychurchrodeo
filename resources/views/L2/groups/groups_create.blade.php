@extends('layouts.producer')

@section('content')
<div class="container-fluid">

    <x-session-alerts />

    <h1> Add new group </h1>
    <hr>
    <div class="card">
        <div class="card-body">

            <form method="POST" action="{{ route('L2.groups.store', $organization->id) }}">
                @csrf

                @include( 'L2.groups.form_fields' )

                <hr>

                <x-form.buttons submit-name="Add" :cancel-url="route('L2.groups.index', $organization->id)" />

            </form>

        </div>
    </div>

</div>
@endsection
