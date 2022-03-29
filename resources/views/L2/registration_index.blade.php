@extends('layouts.producer')

@section('content')
<div class="container-fluid">

    <x-session-alerts />

    <h1> Rodeo registration</h1>
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


@include('partials._form_errors')


            <h2 class="font-weight-bold my-1 mt-3" style="font-size: 1em;"> Register for events </h2>
            <hr class="py-2">
            @foreach( $rodeo->competitions->pluck('group')->unique()->sortBy('name', SORT_NATURAL) as $group )

                <h3 class="font-weight-bold my-1 mt-3" style="font-size: 1em;"> {{ $group->name }} </h3>

                <div class="card mb-4">
                    <div class="card-body">
                        @foreach( $rodeo->competitions->where('group_id', $group['id'])->sortBy('event.name', SORT_NATURAL) as $competition )
                            <div class="mb-3">
                                <h4 class="font-weight-bold my-1" style="font-size: 1em;"> {{ $competition->event->name }} </h4>
                                <hr class="my-1">
                                @if( $competition->allow_multiple_entries_per_contestant && (null === $competition->max_entries_per_contestant || $competition->max_entries_per_contestant > 1) )
                                    <div class="mb-1">
                                        <small class="text-muted"> 
                                            {{ null !== $competition->max_entries_per_contestant ? $competition->max_entries_per_contestant : 'Multiple' }} entries allowed.
                                        </small>
                                    </div>
                                @endif
                                <div>
                                    ${{ $competition->entry_fee ? $competition->entry_fee : '0.00' }}
                                </div>

                                @php
                                    $competitionEntries = $entries->where('competition_id', $competition->id);
                                @endphp
                                @foreach( $competitionEntries as $entry )
                                    <div class="border rounded d-none">
                                        @foreach( $entry->instances as $instance ) 
                                            {{ $instance->starts_at }}
                                        @endforeach
                                    </div>
                                @endforeach

                                @php
                                    $showEntryForm = false;

                                    if( $competitionEntries->count() < 1 )
                                    {
                                        $showEntryForm = true;
                                    }

                                    if( $competition->allow_multiple_entries_per_contestant  &&  null === $competition->max_entries_per_contestant )
                                    {
                                        $showEntryForm = true;
                                    }

                                    if( $competition->allow_multiple_entries_per_contestant  &&  $competition->max_entries_per_contestant > $competitionEntries->count() )
                                    {
                                        $showEntryForm = true;
                                    }
                                @endphp

                                @if( $showEntryForm )
                                    <a href="{{ route('L2.registration.entries.create', [$organization, $contestant, $competition]) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-plus pr-1"></i>
                                        @if( $competitionEntries->count() > 0 )                                            
                                            Add Entry
                                        @else
                                            Enter
                                        @endif
                                    </a>
                                @endif

                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach


{{--
            <h2 class="font-weight-bold my-1 mt-3" style="font-size: 1em;"> Register for events </h2>
            <hr class="py-2">
           @foreach( $groups->sortBy('name', SORT_NATURAL) as $group )   

                <h3 class="font-weight-bold my-1 mt-3" style="font-size: 1em;"> {{ $group->name }} </h3>

                <div class="card mb-4">
                    <div class="card-body">
                        @foreach( $events as $event )

                            @if( $competition = $competitions->where('group_id', $group->id)->where('event_id', $event->id)->first() )
                            <div class="mb-4"> 
                                #{{ $competition->id }} {{ $event->name }}
                                <hr class="my-1">
                                ${{ $competition->entry_fee ? $competition->entry_fee : '0.00' }}<br>  

                                <div class="my-2">
                                    Entry details
                                    <div class=" px-3 py-2 border rounded">
                                            <div class="mb-2">
                                                Select day
                                                <hr class="mt-0 mb-1"> 
                                                @foreach( $competition->instances as $instance )
                                                    <input type="checkbox">
                                                    <x-rodeo-date :date="$instance->starts_at" />
                                                    <br>
                                                @endforeach    
                                            </div>

                                            <div class="mb-2">
                                                Position
                                                <hr class="mt-0 mb-1">
                                                <input type="radio"> Header <br>
                                                <input type="radio"> Heeler
                                            </div>

                                            <div class="my-2">
                                                Additional notes/requests
                                                <textarea class="form-control"></textarea>    
                                            </div>                            
                                    </div>
                                </div>

                                <button class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-plus"></i>
                                    Add
                                </button>
                            </div>
                            @endif 

                        @endforeach
                    </div>
                </div>
            @endforeach
--}}



        </div><!--/col-->
    </div><!--/row-->

</div>
@endsection
