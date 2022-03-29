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


    <h3> Team assignment </h3>
    <div class="card">
        <div class="card-body">

            <form method="post" action="{{ route('L2.team.entries.store', [$organization, $competition]) }}">
                @csrf

                <table class="table">
                    <tbody class="border-top-0">
                        <tr>
                            <th colspan="5" class="font-weight-bold border-top-0">
                                Header
                            </th>
                            <th class="border-top-0"> Requested teammate </th>
                        </tr>
                        @if( $headerEntries->count() < 1 )
                            <tr>
                                <td colspan="6" class="text-secondary" style="font-style: italic;">
                                    There are no entries available for the header position.
                                </td>
                            </tr>
                        @endif
                        @foreach( $headerEntries as $entry )
                            @php
                                $checked = isset($value)  &&  $value == $entry->id  ?  true  :  false;
                                if( old() )
                                {
                                    $checked = $entry->id == old('header') ? true : false;
                                }
                            @endphp
                            @include('partials._team_roping_entry_row', [ 'name' => 'header', 'entry' => $entry, 'checked' => $checked ])
                        @endforeach
                        @error('header')
                            <tr>
                                <td colspan="6"> 
                                    <strong class="invalid-feedback-text">{{ $message }}</strong>
                                </td>
                            </tr>
                        @enderror
                        @error('header.*')
                            <tr>
                                <td colspan="6"> 
                                    <strong class="invalid-feedback-text">{{ $message }}</strong>
                                </td>
                            </tr>
                        @enderror
                    </tbody>


                    <tbody class="border-top-0">
                        <tr>
                            <th colspan="5" class="font-weight-bold border-top-0 pt-4">
                                Heeler
                            </th>
                            <th class="border-top-0"> Requested teammate </th>
                        </tr>     
                        @if( $heelerEntries->count() < 1 )
                            <tr>
                                <td colspan="6" class="text-secondary" style="font-style: italic;">
                                    There are no entries available for the heeler position.
                                </td>
                            </tr>
                        @endif
                        @foreach( $heelerEntries as $entry )
                            @php
                                $checked = isset($value)  &&  $value == $entry->id  ?  true  :  false;
                                if( old() )
                                {
                                    $checked = $entry->id == old('heeler') ? true : false;
                                }
                            @endphp
                            @include('partials._team_roping_entry_row', [ 'name' => 'heeler', 'entry' => $entry, 'checked' => $checked ])
                        @endforeach
                        @error('heeler')
                            <tr>
                                <td colspan="6"> 
                                    <strong class="invalid-feedback-text">{{ $message }}</strong>
                                </td>
                            </tr>
                        @enderror
                        @error('heeler.*')
                            <tr>
                                <td colspan="6"> 
                                    <strong class="invalid-feedback-text">{{ $message }}</strong>
                                </td>
                            </tr>
                        @enderror                      
                    </tbody>
                </table>


                <div class="mb-3">
                    <div class="form-group {{ $errors->has('instance') ? 'is-invalid' : '' }}">
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
                    @error('instance')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror 
                </div>

                <hr class="mt-1">
                <x-form.buttons submit-name="Submit" :cancel-url="route('L2.entries.index', [$organization, $competition])" />

            </form>
        </div>
    </div>

</div>
@endsection
