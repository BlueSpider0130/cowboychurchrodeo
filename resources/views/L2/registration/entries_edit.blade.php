@extends('layouts.producer')

@section('content')
<div class="container-fluid">

    <x-session-alerts />

    <h1> Rodeo registration</h1>
    <hr>
    @include('partials.registration.rodeo_info_card')

    <h2 class="font-weight-bold my-1" style="font-size: 1em;"> Contestant info </h2>
    @include('partials.registration.contestant_info_card')


    <h2 class="font-weight-bold my-1 mt-5" style="font-size: 1em;"> Events </h2>    
    <hr>
    <div class="card">
        <div class="card-header bg-white font-weight-bold">
            {{ $competition->group->name }} &nbsp;&ndash;&nbsp; {{ $competition->event->name }}
        </div>
        <div class="card-body">

            <form method="post" action="{{ route('L2.registration.entries.update', [$organization->id, $entry->id]) }}">
                @method('patch')
                @csrf()

                <x-fields.instance-select :instances="$competition->instances" :value="$entry->instance_id" class="mb-3" />

                @if( $competition->event->is_team_roping )

                    <x-fields.team-roping-position :value="$entry->position" class="mb-3" />

                @endif

                <hr>
                <x-form.buttons 
                    submit-name="Save" 
                    cancel-url="{{ route('L2.registration.entries.index', [$organization->id, $rodeo->id, $contestant->id]) }}" 
                />

            </form>

        </div>
    </div>

</div>
@endsection

