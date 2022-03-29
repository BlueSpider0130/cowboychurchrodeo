@extends('layouts.producer')

@section('content')
<div class="container-fluid py-4">

    <x-session-alerts />

    <h1> {{ $competition->group ? $competition->group->name.' - ' : '' }} {{ $competition->event->name }} </h1>
    <hr>
    <h2 class="d-none"> Rodeo </h2>
    <div class="card mb-5">
        <div class="card-body">

            {{ $rodeo->name }} 
            <hr class="my-1">
            <div>
                {{ $rodeo->starts_at ? $rodeo->starts_at->toFormattedDateString() : 'TBA'}} 
                &ndash; 
                {{ $rodeo->ends_at ? $rodeo->ends_at->toFormattedDateString() : 'TBA' }}
            </div>

            @if( $rodeo->description )
                <p class="mt-2">{{ $rodeo->description }}</p>
            @endif

            @if( $rodeo->entry_fee )
                <table class="mt-2"> 
                    <tr>
                        <td class="pr-2"> Rodeo entry fee: </td>
                        <td> ${{ $rodeo->entry_fee ? number_format( $rodeo->entry_fee, 2) : '0.00' }} </td>
                    </tr>
                </table>
            @endif

        </div>
    </div><!--/card-->

    <h3> Entry </h3>
    <div class="card">
        <div class="card-body">

            <form method="post" action="{{ route('L2.entries.update', [$organization, $entry]) }}">
                @method('put')
                @csrf

                <h4 class="h-reset font-weight-bold" style="font-size: 1.1rem">
                    {{ $entry->contestant->lexical_name_order }}
                </h4>
                <hr class="my-2">
                <div class="mb-4">
                    {{ $entry->contestant->birthdate  ?  'Birthdate: '.$entry->contestant->birthdate->format('m/d/Y') :  '' }}
                </div> 

                @if( $competition->event->team_roping )
                    @push('body')
                        <script type="text/javascript">
                            function togglePositionBadges( position ) 
                            {
                                var headerBadge = document.getElementById('header-radio-badge');
                                var heelerBadge = document.getElementById('heeler-radio-badge');

                                if( 'header' == position ) 
                                {
                                    headerBadge.classList.remove('badge-outline-header');
                                    headerBadge.classList.add('badge-header');
                                    heelerBadge.classList.remove('badge-heeler');
                                    heelerBadge.classList.add('badge-outline-heeler');
                                }
                                else if( 'heeler' == position )
                                {
                                    headerBadge.classList.remove('badge-header');
                                    headerBadge.classList.add('badge-outline-header');
                                    heelerBadge.classList.remove('badge-outline-heeler');
                                    heelerBadge.classList.add('badge-heeler');                               
                                }
                                else
                                {
                                    headerBadge.classList.remove('badge-header');
                                    heelerBadge.classList.remove('badge-header');
                                    headerBadge.classList.add('badge-outline-header');
                                    heelerBadge.classList.add('badge-outline-heeler');                                
                                }                                    
                            }
                        </script>
                    @endpush
                    @php
                        $checked = old() ? old('position') : $entry->position;
                    @endphp
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
                                    @if( 'header' == $checked ) checked @endif
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
                                    @if( 'heeler' == $checked ) checked @endif
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
                                    @if( null === $checked || !in_array($checked, ['header', 'heeler']) ) checked @endif
                                >
                                <label class="form-check-label" for="any-radio">
                                    Any
                                </label>
                            </div>                   
                        </div>
                        <x-form.error name="position" />
                    </div>
                @endif


                <div class="form-group mb-3">
                    <legend class="legend-reset font-weight-bold"> 
                        Entry fee
                    </legend>                    
                    <hr class="my-2">
                    <div class="form-group form-check  {{ $errors->has('no_fee') ? 'is-invalid' : '' }}">
                        <input type="checkbox" class="form-check-input" id="no-fee" name="no_fee" @if(old('no_fee', $entry->no_fee)) checked @endif>
                        <label class="form-check-label" for="no-fee"> None </label>
                        <small id="no-fee-help" class="form-text text-muted">* Contestant will not be charged entry fee.</small>
                    </div>
                    <x-form.error name="no_fee" />
                </div>


                <div class="form-group mb-3">
                    <legend class="legend-reset font-weight-bold"> 
                        Score
                    </legend>                    
                    <hr class="my-2">
                    <div class="form-group form-check {{ $errors->has('no_score') ? 'is-invalid' : '' }}">
                        <input type="checkbox" class="form-check-input" id="no-score" name="no_score"`@if(old('no_score', $entry->no_score)) checked @endif>
                        <label class="form-check-label" for="no-score"> None </label>
                        <small id="no-score-help" class="form-text text-muted">* Contestant is participating "for fun".</small>
                    </div>
                    <x-form.error name="no_score" />
                </div>


                <div class="form-group mb-3 {{ $errors->has('instance') ? 'is-invalid' : '' }}">
                    <legend class="legend-reset font-weight-bold"> 
                        Day
                    </legend>                    
                    <hr class="my-2">
                    @php
                        $checked = old() ? old('instance') : $entry->instance_id;
                    @endphp
                    @foreach( $competition->instances()->whereNotNull('starts_at')->orderBy('starts_at')->get() as $instance )
                        <div class="form-check">                        
                            <input 
                                class="form-check-input" 
                                type="radio" 
                                name="instance" 
                                id="instance-radio-{{ $instance->id }}" 
                                value="{{ $instance->id }}"
                                required
                                @if($checked == $instance->id) checked @endif
                            >
                            <label class="form-check-label" for="instance-radio-{{ $instance->id }}">
                                <x-rodeo-date :date="$instance->starts_at" /> 
                            </label>
                        </div>                        
                    @endforeach
                </div>
                <x-form.error name="instance" />

                <hr class="mt-1">
                <x-form.buttons submit-name="Update" :cancel-url="route('L2.entries.index', [$organization, $competition])" />

            </form>
        </div>
    </div>

</div>
@endsection
