@extends('layouts.producer')

@section('content')
<div class="container-fluid">

    <x-session-alerts />

    <h1> Edit group </h1>
    <hr>
    <div class="card">
        <div class="card-body">

            <form method="POST" action="{{ route('L2.groups.update', [$organization->id, $group->id]) }}">
                @method('PUT')
                @csrf

                @include( 'L2.groups.form_fields' )

                <hr>

                <x-form.buttons submit-name="Save" :cancel-url="route('L2.groups.index', $organization->id)" />

            </form>

        </div>
    </div>

</div>
@endsection
