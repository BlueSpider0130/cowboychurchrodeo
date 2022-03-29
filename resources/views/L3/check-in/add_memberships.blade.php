@extends('layouts.producer')

@section('content')
<div class="container-fluid" style="position: relative;">
 
    <div>
        <h1> {{ $rodeo->name ? $rodeo->name : 'Rodeo #'.$rodeo->id }} </h1>
        <hr class="mb-1">
        <x-rodeo-date :date="$rodeo->starts_at" /> &ndash; <x-rodeo-date :date="$rodeo->ends_at" />
    </div>

    <h2 class="mt-5"> Contestants to check in </h2>
    <div class="card">
        <div class="card-body">
            @foreach( $contestants as $contestant )
                {{ $contestant->lexical_name_order }} <br>
            @endforeach
        </div>
    </div>

    <form method="get" action="{{ route('L3.check-in.summary', [$organization, $rodeo]) }}">
        @csrf()
        <input type="hidden" name="memberships_checked" value="1" />
        @foreach( $contestants as $contestant )
            <input type="hidden" name="contestants[]" value="{{ $contestant->id }}" />
        @endforeach

        <h2 class="mt-5"> Add membership fee for contestants</h2>
        <div class="card">
            <div class="card-body">

                @foreach( $contestants as $contestant )
                    <div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="contestant-checkbox-{{ $contestant->id }}" name="memberships[]" value="{{ $contestant->id }}">
                            <label class="form-check-label" for="contestant-checkbox-{{ $contestant->id }}">
                                {{ $contestant->lexical_name_order }}
                            </label>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary"> Proceed </button>
            <a href="{{ route('L3.check-in.contestants', [$organization->id, $rodeo->id]) }}" class="btn btn-outline-secondary"> Cancel </a>
        </div>

    </form>

</div>
@endsection