@extends('layouts.producer')

@section('content')
<div class="container-fluid">

    <x-session-alerts />

    <h1>
        @if( $competition->group )
            {{ $competition->group->name }} &ndash;
        @endif
        {{ $competition->event->name }}
    </h1>
    <hr>
    <div class="row mb-5">
        <div class="col-12 col-md-10 col-lg-8">        

            <div class="card mb-4">
                <div class="card-body">
                    <strong style="font-weight: bold;"> {{ $rodeo->name ? $rodeo->name : "Rodeo #{$rodeo->id}" }} </strong> <br> 
                    <x-rodeo-date :date="$rodeo->starts_at" /> &ndash; <x-rodeo-date :date="$rodeo->ends_at" /> <br>
                    @if( $rodeo->entry_fee )
                        Entry fee: {{ $rodeo->entry_fee }} <br>
                    @endif
                    @if( $rodeo->opens_at  &&  $rodeo->opens_at > \Carbon\Carbon::now() )
                        Registration opens: &nbsp; {{ $rodeo->opens_at->toDayDateTimeString() }} <br>
                    @elseif( $rodeo->closes_at  &&  $rodeo->closes_at > \Carbon\Carbon::now() )
                        Registration closes: &nbsp; {{ $rodeo->closes_at->toDayDateTimeString() }} <br>
                    @elseif( $rodeo->starts_at  &&  $rodeo->starts_at > \Carbon\Carbon::now() )
                        Rodeo starts: &nbsp; {{ $rodeo->starts_at->toDayDateTimeString() }} <br>
                    @else
                        @if( $rodeo->starts_at  &&  $rodeo->starts_at <= \Carbon\Carbon::now() )
                            Rodeo start
                        @endif
                    @endif                                        
                </div>
            </div>

            {{-- @include('partials._constestant_info') --}}
            <h2 class="font-weight-bold my-1 mt-3" style="font-size: 1em;"> Contestant info </h2>
            <div class="card mb-4">
                <div class="card-body">

                    <p>
                        {{ $contestant->last_name }}, {{ $contestant->first_name }} <br>
                        {{ $contestant->birthdate ? $contestant->birthdate->toFormattedDateString() : '' }}
                    </p>

                    <address class="mb-0 pb-0">
                        @if($contestant->address_line_1)
                            {{ $contestant->address_line_1 }}<br>
                        @endif

                        @if($contestant->address_line_2)
                            {{ $contestant->address_line_2 }}<br>
                        @endif
                        
                        @if($contestant->city)
                            {{ $contestant->city }}, 
                        @endif
                        @if($contestant->state)
                            {{ $contestant->state }} 
                        @endif
                        @if($contestant->postcode) 
                            {{ $contestant->postcode }}
                        @endif                    
                        @if($contestant->city || $contestant->state || $contestant->postcode)
                            <br>
                        @endif
                    </address>

                </div>
            </div>

            <div class="card">
                <div class="card-body">

                    @if( $competition->group )
                        {{ $competition->group->name }} &ndash;
                    @endif
                    {{ $competition->event->name }}
                    <hr class="my-2">
                    <p>{{ $competition->description }}</p>

                    <p>${{ $competition->entry_fee }}</p>

                    <form method="post" action="">
                        @csrf






                        @if( $competition->event->team_roping )
                            <div class="mb-3">
                                <div class="form-group {{ $errors->has('position') ? 'is-invalid' : '' }}">
                                    <legend class="legend-reset font-weight-bold"> 
                                        Position
                                    </legend>
                                    
                                    <hr class="my-2">

                                    <div class="form-check">
                                        <input 
                                            class="form-check-input" 
                                            type="radio" 
                                            name="position" 
                                            id="header-radio" 
                                            value="header"
                                            onchange="if( this.checked ) { togglePositionBadges('header');  } else { togglePositionBadges(); }"
                                            required 
                                            @if( 'header' == old('position') ) checked @endif
                                        >
                                        <label class="form-check-label" for="header-radio">
                                            <span class="badge {{ 'header' == old('position') ? 'badge-header' : 'badge-outline-header' }}" id="header-radio-badge"> Header </span>
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input 
                                            class="form-check-input" 
                                            type="radio" 
                                            name="position" 
                                            id="heeler-radio" 
                                            value="heeler"
                                            onchange="if( this.checked ) { togglePositionBadges('heeler');  } else { togglePositionBadges(); }"
                                            required 
                                            @if( 'heeler' == old('position') ) checked @endif
                                        >
                                        <label class="form-check-label" for="heeler-radio">
                                            <span class="badge {{ 'heeler' == old('position') ? 'badge-heeler' : 'badge-outline-heeler' }}" id="heeler-radio-badge"> Heeler </span>
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input 
                                            class="form-check-input" 
                                            type="radio" 
                                            name="position" 
                                            id="any-radio" 
                                            value="0"
                                            onchange="togglePositionBadges();"
                                            required 
                                            @if( old()  &&  !in_array(old('position'), ['header', 'heeler']) ) checked @endif
                                        >
                                        <label class="form-check-label" for="any-radio">
                                            Any
                                        </label>
                                    </div>                   
                                </div>
                                <x-form-error name="position" />
                            </div>
                        @endif


                        <div class="form-group mb-3">
                            <legend class="legend-reset font-weight-bold"> 
                                Entry fee
                            </legend>                    
                            <hr class="my-2">
                            <div class="form-group form-check  {{ $errors->has('no_fee') ? 'is-invalid' : '' }}">
                                <input type="checkbox" class="form-check-input" id="no-fee" name="no_fee" @if(old('no_fee')) checked @endif>
                                <label class="form-check-label" for="no-fee"> None </label>
                                <small id="no-fee-help" class="form-text text-muted">* Contestant will not be charged entry fee.</small>
                            </div>
                            <x-form-error name="no_fee" />
                        </div>


                        <div class="form-group mb-3">
                            <legend class="legend-reset font-weight-bold"> 
                                Score
                            </legend>                    
                            <hr class="my-2">
                            <div class="form-group form-check {{ $errors->has('no_score') ? 'is-invalid' : '' }}">
                                <input type="checkbox" class="form-check-input" id="no-score" name="no_score"`@if(old('no_score')) checked @endif>
                                <label class="form-check-label" for="no-score"> None </label>
                                <small id="no-score-help" class="form-text text-muted">* Contestant is participating "for fun".</small>
                            </div>
                            <x-form-error name="no_score" />
                        </div>


                        <div class="form-group mb-3 {{ $errors->has('instance') ? 'is-invalid' : '' }}">
                            <legend class="legend-reset font-weight-bold"> 
                                Day
                            </legend>                    
                            <hr class="my-2">
                            @foreach( $competition->instances()->whereNotNull('starts_at')->orderBy('starts_at')->get() as $instance )
                                <div class="form-check">                        
                                    <input 
                                        class="form-check-input" 
                                        type="radio" 
                                        name="instance" 
                                        id="instance-radio-{{ $instance->id }}" 
                                        value="{{ $instance->id }}"
                                        required
                                        @if(old('instance') == $instance->id) checked @endif
                                    >
                                    <label class="form-check-label" for="instance-radio-{{ $instance->id }}">
                                        <x-rodeo-date :date="$instance->starts_at" /> 
                                    </label>
                                </div>                        
                            @endforeach
                        </div>
                        <x-form-error name="instance" />



                        <hr>
                        <x-form-buttons submit-name="Submit" :cancel-url="route('L2.registration.entries.create', [$organization, $contestant, $competition])" />

                    </form>
                </div>
            </div>



        </div><!--/col-->
    </div><!--/row-->

</div>
@endsection
