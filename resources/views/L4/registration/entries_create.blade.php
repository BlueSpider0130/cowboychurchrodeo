@extends('layouts.app')

@section('content')
<div class="container">

    <x-session-alerts />

    <h1> Rodeo registration</h1>
    <hr>
    <h2 class="font-weight-bold my-1" style="font-size: 1em;"> Rodeo info </h2>    
    @include('partials.registration.rodeo_info_card')
    
    <h2 class="font-weight-bold my-1" style="font-size: 1em;"> Contestant info </h2>
    @include('partials.registration.contestant_info_card')

    <h2 class="font-weight-bold my-1" style="font-size: 1em;"> Events </h2>    
    <div class="card">
        <div class="card-header bg-white font-weight-bold">
            <span style="font-size: 1.1rem">
                {{ $competition->group->name }} &nbsp;&ndash;&nbsp; {{ $competition->event->name }}
            </span>
        </div>
        <div class="card-body">

            <form method="post" action="{{ route('L4.registration.entries.store', [$organization->id, $rodeo->id, $contestant->id, $competition->id]) }}">
                @csrf()

                <x-fields.instance-select :instances="$competition->instances" class="mb-3" />

                @if( $competition->event->is_team_roping )
                                
                    <x-fields.team-roping-position class="mb-3" />

                    <div class="row mt-3">
                        <div class="col-12 col-md-6">
                            <label class="font-weight-bold optional"> Requested teammate </label>
                            <textarea name="requested_teammate" class="form-control @error('requested_teammate') is-invalid @endif">{{ old('requested_teammate') }}</textarea>
                            @error('requested_teammate')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror                        
                        </div>
                    </div>

                @endif 

                <hr>
                <x-form.buttons 
                    submit-name="Register" 
                    cancel-url="{{ route('L4.registration.entries.index', [$organization->id, $rodeo->id, $contestant->id]) }}" 
                />

            </form>

        </div>
    </div>

</div>
@endsection

