@extends('layouts.admin')

@section('content')

<nav class="admin-breadcrumbs" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"> <a href="{{ route('admin.users.index') }}"> Users </a> </li>
        <li class="breadcrumb-item active" aria-current="page"> Edit user </li>
    </ol>
</nav>

<div class="container-fluid py-4">
    
    <h1> Edit user </h1>
    <hr>

    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
        @method('PATCH')
        @csrf

        <div class="row">
            <div class="col-12 col-md-6 mb-3">

                <label for="first_name"> First name </label>
                <x-form.input type="text" id="first_name" name="first_name" :value="$user->first_name" required />

            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-6 mb-3">
                
                <label for="last_name"> Last name </label>
                <x-form.input type="text" id="last_name" name="last_name" :value="$user->last_name" required />
            </div>
        </div>


        <div class="row">
            <div class="col-12 col-md-6 mb-3">

                <label for="email"> E-Mail Address </label>
                <x-form.input type="email" id="email" name="email" :value="$user->email" required />

            </div>
        </div>

        <hr>

        <x-form.buttons submit-name="Save" :cancel-url="route('admin.users.show', $user->id)" />

    </form>

</div>
@endsection

