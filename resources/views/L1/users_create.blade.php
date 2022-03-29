@extends('layouts.admin')

@section('content')

<nav class="admin-breadcrumbs" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"> <a href="{{ route('admin.users.index') }}"> Users </a> </li>
        <li class="breadcrumb-item active" aria-current="page"> Create new user </li>
    </ol>
</nav>

<div class="container-fluid py-4">
    
    <h1> Create new user </h1>
    <hr>

    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        <div class="row">
            <div class="col-12 col-md-6 mb-3">

                <label for="first_name"> First name </label>
                <x-form.input type="text" id="first_name" name="first_name" placeholder="First name..." autocomplete="first_name" autofocus required />

            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-6 mb-3">
                
                <label for="last_name"> Last name </label>
                <x-form.input type="text" id="last_name" name="last_name" placeholder="Last name..." autocomplete="last_name" autofocus required />

            </div>
        </div>


        <div class="row">
            <div class="col-12 col-md-6 mb-3">

                <label for="email"> E-Mail Address </label>
                <x-form.input type="email" id="email" name="email" placeholder="xxxx@xxxx.xxx" required />

            </div>
        </div>


        <div class="row">
            <div class="col-12 col-md-6 mb-3">
                
                <label for="password"> Password </label>
                <x-form.input type="password" id="password" name="password" required />

            </div>
        </div>


        <div class="row">
            <div class="col-12 col-md-6 mb-3">                

                <label for="password-confirm"> Confirm Password </label>
                <x-form.input type="password" id="password-confirm" name="password_confirmation" required />

            </div>
        </div>

        <hr>

        <x-form.buttons submit-name="Create" :cancel-url="route('admin.users.index')" />

    </form>

</div>
@endsection

