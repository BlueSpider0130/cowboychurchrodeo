@extends('layouts.producer')

@section('content')
<div class="container-fluid">

    <x-session-alerts />

    <h1> Edit document details </h1>
    <hr>
    <div class="card">
        <div class="card-body">

            <form method="POST" action="{{ route('L2.documents.update', [$organization->id, $document->id]) }}" enctype="multipart/form-data">
                @method('PATCH')
                @csrf

                <div class="row mb-2">
                    <div class="col-12 col-md-6 mb-3">
                        <label for="name"> Name </label>
                        <x-form.input type="text" id="name" name="name" value="{{ $document->name }}" required />
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-12 col-md-6 mb-3">                
                        <label for="description" class="optional"> Description </label>
                        <x-form.textarea id="description" name="description" value="{{ $document->description }}" rows="3" />
                    </div>
                </div>


                <div class="row mb-2">
                    <div class="col-12 col-md-6 mb-3">                
                        
                        <label for="description"> File </label> <br>
                        
                        <div>
                            <a href="{{ route('documents.download', $document->id) }}">{{ $document->filename }}</a> <br>
                            <button class="btn btn-outline-secondary btn-sm mt-1" 
                                onclick="
                                    this.parentElement.style.display='none'; 
                                    document.getElementById('file-input').style.display='block'; 
                                    document.getElementById('file').click();  
                                    return false;
                                "
                            > Change </button>
                        </div>

                        <div id="file-input" style="display: none;">
                            <x-form.file-input id="file" name="file" />
                        </div>

                    </div>
                </div>



                <hr>

                <x-form.buttons submit-name="Save" :cancel-url="route('L2.documents.index', $organization)" />

            </form>

        </div>
    </div>
</div>
@endsection
