@extends('layouts/admin')

@section('content')
<nav aria-label="breadcrumb" style=" margin: 0 -15px -1rem -15px;">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.task.index.open') }}"> Tasks </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page"> Add task </li>
    </ol>
</nav>

<div class="container-fluid py-4">

    <h4> Add new task </h4>
    <hr>
    <form method="post">
        @csrf 

        <div class="row">
            <div class="col-md-4">
                
                <label> Type </label>
                @php
                    $options = \App\AdminTaskType::pluck('name', 'id')->toArray();
                @endphp
                <x-form.select name="type" :options="$options" required />

                <br>

                <label> Priority </label>
                @php
                    $options = \App\AdminTaskPriority::pluck('name', 'id')->toArray();
                @endphp
                <x-form.select name="priority" :options="$options" required />
                <br>

            </div>
        </div>

        <label> Page </label>
        <x-form.input name="page" />
        <br>

        <label> Description </label>
        <x-form.textarea name="description" rows="10" required />
        <br>

        <x-form.buttons submit-name="Create" cancel-name="Cancel" :cancel-url="route('admin.task.index.open')" />

    </form>

</div>
@endsection
