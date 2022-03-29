@extends('layouts.admin')

@section('content')

<nav class="admin-breadcrumbs" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"> <a href="{{ route('admin.organizations.index') }}"> Organizations </a> </li>
        <li class="breadcrumb-item active" aria-current="page"> Add new organization </li>
    </ol>
</nav>

<div class="container-fluid py-4">

    <x-session-alerts />
    
    <h1> Add organization </h1>
    <hr>
    <form method="POST" action="{{ route('admin.organizations.store') }}">
        @csrf

        @include( 'partials.organization.form.account_info' )

        <div class="mt-5">
            <label for="notes-input" class="font-weight-bold my-0 py-0"> Admin notes </label>
            <hr class="mt-2 mb-3">
            <x-form.textarea name="admin_notes" id="notes-input" />
        </div>

        <hr>

        <x-form.buttons submit-name="Save" :cancel-url="route('admin.organizations.index')" />

    </form>
</div>
@endsection

