@extends('layouts.producer')

@section('content')
<div class="container-fluid py-4">

    <x-session-alerts />
    
    <h1> {{ $organization->name }} </h1>

    <div class="card">
        <div class="card-body">

            <form method="POST" action="{{ route('L2.organizations.update', $organization) }}">
                @method('PATCH')
                @csrf

                <div class="mt-2">
                    <h2 class="h-reset font-weight-bold"> Account info </h2>
                    <hr>                    
                    @include( 'partials.organization.form.account_info' )
                </div>

                <hr>
                <x-form.buttons submit-name="Save" :cancel-url="route('producer.home', $organization)" />

            </form>

        </div>
    </div>

</div>
@endsection

