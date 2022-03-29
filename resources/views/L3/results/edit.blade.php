@extends('layouts.producer')

@section('content')
<div class="container-fluid">

    <h1> Record results </h1>
    <hr class="mb-2">

    <div class="card mb-5">
        <div class="card-body">
            {{ $rodeo->name ? $rodeo->name : "Rodeo #{$rodeo->id}" }} <br>
            <x-rodeo-dates :model="$rodeo" />
        </div>
    </div>

    <h2> {{ $competition->group->name }} &ndash; {{ $competition->event->name }} </h2>
    <hr>
    <form method="post" action="{{ route('L3.results.update', [$organization->id, $rodeo->id, $competition->id]) }}">
        @method('patch')
        @csrf()

        <table class="table table-responsive-cards bg-white border">
            <thead>
                <tr>
                    <th> Entry </th>
                    <th> Contestant </th>
                    <th> Score </th>
                </tr>
            </thead>
            <tbody>
                @foreach( $entries as $entry )
                    <tr>
                        <td> #{{ $entry->id }} </td>
                        <td> {{ $entry->contestant->lexical_name_order }} </td>
                        <td>
                            <input 
                                type="text" 
                                class="form-control @error('entries.'.$entry->id) is-invalid @enderror"
                                name="entries[{{ $entry->id }}]"
                                value="{{ old('entries.'.$entry->id, $entry->score) }}"
                                placeholder="Enter score..."
                            >
                            @error('entries.'.$entry->id) 
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>                            
                            @enderror
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <hr>
        <x-form.buttons submit-name="Save" :cancel-url="route('L3.results.show', [$organization->id, $rodeo->id, $competition->id])" />

    </form>

</div>
@endsection