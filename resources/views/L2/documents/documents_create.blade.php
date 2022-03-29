@extends('layouts.producer')

@section('content')
<div class="container-fluid">

    <x-session-alerts />

    <h1> Upload new document </h1>
    <hr>
    <div class="card">
        <div class="card-body">

            <form method="POST" action="{{ route('L2.documents.store', $organization->id) }}" enctype="multipart/form-data">
                @csrf

                <div class="row mb-2">
                    <div class="col-12 col-md-6 mb-3">
                        <label for="name"> Name </label>
                        <x-form.input type="text" id="name" name="name" required />
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-12 col-md-6 mb-3">                
                        <label for="description" class="optional"> Description </label>
                        <x-form.textarea id="description" name="description" rows="3" />
                    </div>
                </div>

                <x-form.file-input id="file" name="file" required />

                <hr>

                <x-form.buttons submit-name="Save" :cancel-url="route('L2.documents.index', $organization)" />

            </form>

        </div>
    </div>
</div>
@endsection
