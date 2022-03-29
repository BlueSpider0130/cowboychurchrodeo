@extends('layouts.app')

@section('content')
<div class="container">

    <div class="mb-4">
        <a href="{{ route('toolbox', $organization->id) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-chevron-left"></i>
            Toolbox
        </a>        
    </div> 

    <x-session-alerts />

    <h1> Rodeo registration </h1>

    <ul class="nav nav-tabs mt-5 mb-4">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('L4.registration.home', [$organization->id]) }}"> 
                Registration
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('L4.registration.entered', [$organization->id]) }}"> 
                Registered
            </a>
        </li>
    </ul>

    @if( $rodeoEntries->count() < 1 )
        <p class="my-4 text-muted"> You have not registered for any rodeos. </p>
    @endif

    @foreach($rodeoEntries as $entry)
        <div class="card mb-4"> 

            <div class="card-header bg-white font-weight-bold">
                <div class="float-left">
                    {{ $entry->contestant->lexical_name_order }} 
                </div>
                
                <div class="float-right text-right">
                    <div class="dropdown">
                        <a hre="#" role="button" class="text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-h fa-lg"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right"> 
                            <a class="dropdown-item" href="{{ route('L4.registration.confirmation', [$organization, $entry->rodeo, $entry->contestant_id]) }}">
                                <i class="fas fa-list"></i>
                                View 
                            </a> 
                            <a class="dropdown-item" href="{{ route('L4.registration.show', [$organization, $entry->rodeo, $entry->contestant_id]) }}">
                                <i class="fas fa-edit"></i>
                                Edit 
                            </a> 
                        </div>
                    </div>
                </div>                   
            </div>

            <div class="card-body py-3">
                <div class="float-right text-right">
                    @if( $entry->checked_in_at )
                        <span class="pill-badge-checked-in" title="{{ $entry->checked_in_at->toFormattedDateTime() }}">Checked in</span>
                    @endif
                    @if( $entry->allPaid() )
                        <span class="pill-badge-paid">Paid</span>
                    @endif
                </div>    

                <p class="my-0"> 
                    {{ $entry->rodeo->name  }} <br>
                    <x-rodeo-date :date="$entry->rodeo->starts_at" /> &ndash; <x-rodeo-date :date="$entry->rodeo->ends_at" />
                </p>

                <p class="mt-2 mb-0">
                    Registered: {{ $entry->created_at->toFormattedDateString() }} 
                </p>

                @if( $entry->paid_at )
                    <p class="my-0 mt-2"> 
                        Paid: {{ $entry->paid_at->toFormattedDateString() }}
                    </p>
                @endif

                @if( $entry->checked_in_at )
                    <p>
                        Checked in: {{ $entry->checked_in_at->toFormattedDateString() }}
                    </p>
                @endif
            </div>

        </div>
    @endforeach


</div>
@endsection
