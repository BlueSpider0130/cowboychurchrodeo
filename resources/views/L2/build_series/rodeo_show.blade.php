@extends('layouts.producer')

@section('content')
<div class="container-fluid">

    <x-session-alerts />

    <h1> {{ $rodeo->name ? $rodeo->name : "Rodeo #{$rodeo->id}" }} </h1>
    <hr>
    <div class="text-right">

        <div class="dropdown">
            <a hre="#" role="button" class="text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-ellipsis-h fa-lg"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                
                <a class="dropdown-item" href="{{ route('L2.build.series.show', [$organization, $series]) }}">
                    Return to series
                </a>                                

                <button class="dropdown-item" type="button" onclick="if( confirm('Are you sure you want to delete this rodeo?') ) { document.getElementById('rodeo-delete-form').submit(); }">
                    <span class="text-danger">Delete rodeo</span>
                </button>
                <form method="post" action="{{ route('L2.build.series.rodeos.destroy', [$organization, $series, $rodeo]) }}" class="d-none" id="rodeo-delete-form"> @method('delete') @csrf </form>
            </div>
        </div>
    </div>    

    @include('L2.build_series.__series_info_card', ['series' => $series])

    <div class="row mb-5">
        <div class="col-12 col-md-10 col-lg-8">

                <h2 class="h-reset font-weight-bold my-1"> Rodeo details </h2>
                <div class="card">
                    <div class="card-body">

                        <div class="row"> 
                            <div class="col">
                                {{ $rodeo->name }} 
                            </div>
                            <div class="col-2 text-right">
                                <a href="{{ route('L2.build.series.rodeos.edit', [$organization, $series, $rodeo]) }}" class="text-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>

                        <hr class="my-1">
                        <div>
                            {{ $rodeo->starts_at ? $rodeo->starts_at->toFormattedDateString() : 'TBA'}} 
                            &ndash; 
                            {{ $rodeo->ends_at ? $rodeo->ends_at->toFormattedDateString() : 'TBA' }}
                        </div>

                        @if( $rodeo->description )
                            <p class="mt-2">{{ $rodeo->description }}</p>
                        @endif

                        <table class="mt-2"> 
                            <tr>
                                <td class="pr-2"> Office fee: </td>
                                <td> ${{ $rodeo->office_fee ? number_format( $rodeo->office_fee, 2) : '0.00' }} </td>
                            </tr>
                            <tr>
                                <td class="pr-2"> Registration opens: </td>
                                <td> 
                                    @if( $rodeo->opens_at )
                                        <x-rodeo-date-time :date="$rodeo->opens_at" />
                                    @else
                                        <span class="text-muted" style="font-size: .85rem"> not set (<i>registration opens as soon as is rodeo created</i>) </span>
                                    @endif
                                </td>
                            </tr>
 
                            <tr>
                                <td class="pr-2"> Registration closes: </td>
                                <td> 
                                    @if( $rodeo->closes_at )
                                        <x-rodeo-date-time :date="$rodeo->closes_at" />
                                    @else 
                                        <span class="text-muted" style="font-size: .85rem"> not set (<i>registration will close at rodeo start time</i>) </span>
                                    @endif
                            </td>
                            </tr>                        
                        </table>
                          
                    </div>
                </div><!--/card-->

        </div>
    </div><!--/row--> 

    <div class="row mb-5">
        <div class="col-12 col-md-10 col-lg-8">

               <h2 class="h-reset font-weight-bold my-1"> Office fee </h2>
                <div class="card">
                    <div class="card-body">
                        @if( $rodeo->group_office_fee_exceptions->count() < 1  &&  $rodeo->event_office_fee_exceptions->count() < 1 )
                            <p>Office applies to all groups and events.</p>
                        @else
                        
                            @if( $rodeo->group_office_fee_exceptions->count() > 0 )
                                Office fee not applicable to groups:
                                <hr class="my-2">
                                <ul>
                                    @foreach( $rodeo->group_office_fee_exceptions as $group )
                                        <li>{{ $group->name }}</li>
                                    @endforeach
                                </ul>
                            @endif
                            
                            @if( $rodeo->event_office_fee_exceptions->count() > 0 )
                                Office fee not applicable to events:
                                <hr class="my-2">
                                <ul>
                                    @foreach( $rodeo->event_office_fee_exceptions as $event )
                                        <li>{{ $event->name }}</li>
                                    @endforeach
                                </ul>
                            @endif                            

                        @endif
                        <a href="{{ route('L2.build.series.rodeo.office.fee.edit', [$organization->id, $series->id, $rodeo->id]) }}" class="btn btn-outline-secondary btn-sm"> Change </a>
                    </div>
                </div>
            
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12 col-md-10 col-lg-8">

                <h2 class="h-reset font-weight-bold my-1"> Rodeo events </h2>
                <hr class="mb-4">
                @if( $events->count() < 1 )

                    <p>You have not created any rodeo <a href="{{ route('L2.events.index', $organization) }}">events</a> yet.</p>
                
                @elseif( $groups->count() < 1 )

                    <p>You have not created any <a href="{{ route('L2.groups.index', $organization) }}">groups</a> yet.</p>

                @elseif( $rodeo->starts_at  &&  $rodeo->ends_at  &&  $competitions->count() < 1 )
                    @php
                        $withCompetitions = [];
                        foreach ( $series->rodeos()->with('competitions')->where('id', '!=', $rodeo->id)->get() as $rodeoToCopy) 
                        {
                            if( $rodeoToCopy->competitions->count() > 0 )
                            {
                                $withCompetitions[] = $rodeoToCopy;
                            }
                        }
                    @endphp
                    @if( $withCompetitions )
                        <div class="mb-5 px-3">
                            <p> You can copy events from an existing rodeo and assign them to all rodeo days. </p>

                            <form method="post" action="{{ route('L2.build.series.competitions.copy', [$organization, $rodeo]) }}">
                                @csrf
                                <div class="form-group">
                                    <legend class="legend-reset"> Copy events from rodeo: </legend>
                                    @foreach( $withCompetitions as $rodeoToCopy )
                                        <div class="form-check pl-4">
                                            <input 
                                                class="form-check-input rodeo-copy-radio" 
                                                type="radio" 
                                                name="rodeo" 
                                                id="copy-rodeo-{{ $rodeoToCopy->id }}" 
                                                value="{{ $rodeoToCopy->id }}"
                                                required
                                            >
                                            <label class="form-check-label" for="copy-rodeo-{{ $rodeoToCopy->id }}">
                                                {{ $rodeoToCopy->name }} 
                                                @if( $rodeoToCopy->starts_at && $rodeoToCopy->ends_at )
                                                    <div class="d-inline-block ml-2 text-secondary" style="font-size: .75rem">
                                                        <x-rodeo-date :date="$rodeoToCopy->starts_at" /> &ndash; <x-rodeo-date :date="$rodeoToCopy->ends_at" />
                                                    </div>
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <hr>
                                <button 
                                    type="submit" 
                                    class="btn btn-primary btn-sm"
                                > Copy events </button>
                                <button 
                                    class="btn btn-outline-secondary btn-sm"
                                    onclick="var els = document.getElementsByName('rodeo'); for( var i=0; i < els.length; i++ ) { els[i].checked = false; }"
                                > Cancel </button>

                            </form>
                        </div>
                    @endif
                @endif

                @if( !$rodeo->starts_at  ||  !$rodeo->ends_at  )
                    <p> Rodeo must have a start and end date in order to add events. </p>
                @else                
                    @foreach( $groups as $group )

                        <h3 class="h-reset font-weight-bold mb-1"> {{ $group->name }} </h3>
                        <table class="table bg-white border rounded mb-4 mb-md-5 table-responsive-cards">
                            <tbody>
                                @foreach( $events as $event )
                                    @php 
                                        $competition = $competitions->where('group_id', $group->id)->where('event_id', $event->id)->first();
                                    @endphp
                                    <tr>
                                        <td> 
                                            <span> 
                                                {{ $event->name }} 
                                            </span>
                                            @if( $competition )
                                                <div class="px-4 border-top mt-1 pt-1 mb-3 text-secondary">
                                                    <div class="mb-2">
                                                        Entry fee: ${{ $competition->entry_fee ? number_format($competition->entry_fee, 2) : '0.00' }}
                                                    </div>
                                                    @if( $competition->instances->count() > 0 )
                                                        <div class="mt-1" style="font-size: 1em;"> Days </div>
                                                        <ul class="d-inline-block border-top mt-1 pt-1 pl-4 pr-3">
                                                            @foreach( $competition->instances as $instance )
                                                                <li> <x-rodeo-date :date="$instance->starts_at" /> </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif  
                                                    <div>
                                                        @if( $competition->allow_multiple_entries_per_contestant )
                                                            {{ $competition->max_entries_per_contestant ? $competition->max_entries_per_contestant : 'Multiple'  }}  entries 
                                                        @else
                                                            1 entry
                                                        @endif 
                                                        per contestant allowed.
                                                    </div>
                                                </div>
                                            @endif
                                        </td>

                                        <td>
                                            @if( $event->is_team_roping ) 
                                                <span style="font-style: italic; font-size: .8rem" class="text-muted">
                                                    Team roping
                                                </span>
                                            @endif
                                        </td>

                                        <td class="text-md-right"> 
                                            @if( $competition )

                                                <a href="{{ route('L2.build.series.competitions.edit', [$organization, $competition]) }}"  class=" btn btn-outline-secondary btn-sm"> 
                                                    <i class="fas fa-edit pr-1"></i>
                                                    Edit 
                                                </a>

                                                <x-delete-button
                                                    url="{{ route('L2.build.series.competitions.destroy', [$organization, $competition]) }}" 
                                                    message="Are you sure you want to remove this event from the rodeo?"
                                                    class="btn btn-outline-danger btn-sm"
                                                >
                                                    <i class="fas fa-times pr-1"></i> Remove
                                                </x-delete-button>

                                            @else
                                                @php
                                                    $params = [
                                                        $organization,
                                                        $rodeo,
                                                        $event,
                                                        $group,
                                                    ];
                                                    $url = route('L2.build.series.competitions.create', $params);
                                                @endphp
                                                <a href="{{ $url }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-plus pr-1"></i> 
                                                    Add event to rodeo
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    @endforeach

                    <hr>
                    
                    <a href="{{ route('L2.build.series.show', [$organization, $series]) }}" class="btn btn-primary">
                        Done
                    </a>

                @endif
        </div>
    </div>

</div>
@endsection
