@extends('layouts.producer')

@section('content')
<div class="mt-n4 mx-n4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"> 
                <a href="{{ route('L2.registration.rodeos.index', [$organization->id]) }}"> Rodeos </a> 
            </li>
            <li class="breadcrumb-item"> 
                <a href="{{ route('L2.registration.contestants.index', [$organization->id, $rodeo->id]) }}"> Contestants </a> 
            </li>
            <li class="breadcrumb-item active" aria-current="page"> {{ $contestant->lexical_name_order }} </li>
        </ol>
    </nav>
</div>

<div class="container-fluid">

    <x-session-alerts />

    <h1> Rodeo registration </h1>
    <hr>
    @include('partials.registration.rodeo_info_card')


    <b>Contestant</b>
    <div class="card mb-4">
        <div class="card-body">
            {{ $contestant->lexical_name_order }} <br>
            {{ $contestant->birthdate ? $contestant->birthdate->format('m/d/Y') : '' }}
        </div>        
    </div>

    <b> Check in notes </b>
    <div class="card">
        <div class="card-body">

            <form method="post" action="{{ route('L2.registration.checkin.notes.update', [$organization, $rodeo, $contestant]) }}">
                @method('put')
                @csrf()

                <label>Notes</label>
                <textarea rows="5" name="notes" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $rodeoEntry->check_in_notes) }}</textarea>
                @error('notes')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror   

                <hr>
                <x-form.buttons 
                    submit-name="Save" 
                    cancel-url="{{ route('L2.registration.show', [$organization->id, $rodeo->id, $contestant->id]) }}" 
                />
            </form>

        </div>
    </div>


</div>
@endsection