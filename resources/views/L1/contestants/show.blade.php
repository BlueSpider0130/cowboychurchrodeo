@extends('layouts.admin')

@section('content')
<nav class="admin-breadcrumbs" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"> <a href="{{ route('admin.contestants.index') }}"> Contestants </a> </li>
        <li class="breadcrumb-item active" aria-current="page"> #{{ $contestant->id }} </li>
    </ol>
</nav>

<div class="container-fluid py-4">

    <x-session-alerts />
    
    <div class="row mb-5">
        <div class="col-12 col-md-12 col-xl-8">        

            <div class="card mb-5">
                <div class="card-header bg-white">
                    <h1 class="my-0"> {{ $contestant->last_name}}, {{ $contestant->first_name }} </h1>
                </div>
                <div class="card-body">


                    <div class="row">
                        
                        <div class="col-6 mb-3 col-md-2 mb-md-0">
                            @if($contestant->photo_path)
                                <img 
                                    src="{{  asset('storage/'.$contestant->photo_path) }}" 
                                    alt="Contestant picture" 
                                    class="rounded" 
                                    style="width: 100%"> 
                            @else 
                                <i>no photo</i>
                            @endif
                        </div>

                        <div class="col-12 mb-1 col-md mb-md-0">
                            @if( $contestant->birthdate )
                                <p>
                                    <span class="mr-2 font-weight-bold"> Birthdate: </span> 
                                    {{ $contestant->birthdate->toFormattedDateString() }}
                                </p>
                            @endif
                            <p class="mb-0">
                                <span class="mr-2 font-weight-bold"> Address: </span>
                                <address class="mb-0">
                                    {{ $contestant->address_line_1 }} 
                                    <br>
                                    @if( $contestant->address_line_2 )
                                        {{ $contestant->address_line_2 }} 
                                        <br>
                                    @endif
                                    {{ $contestant->city }}{{ $contestant->city && $contestant->state ? ',' : '' }} 
                                    {{ $contestant->state }} 
                                    {{ $contestant->postcode }} 
                                    <br>
                                </address>
                            </p>
                        </div>

                        <div class="col-12 col-md text-md-right">
                            <hr class="mt-0 mb-2 d-md-none">
                            
                        </div>

                    </div><!--/row-->

                </div>
            </div><!--/card-->


            <h2 class="my-0 font-weight-bold" style="font-size: 1.05em"> Assigned to user accounts </h2>
            <hr class="mt-2 mb-3">
            <div class="card mb-4">
                <div class="card-body">
                    <table>
                        @foreach( $contestant->users as $user )
                            <tr>
                                <td class="pr-3"> {{ $user->name }} </td>
                                <td class="pr-3"> {{ $user->email }} </td>
                                <td>
                                    <a href="#" onclick="if(confirm('Are you sure you want to unassign the contestant from this user?')){ document.getElementById('unassign-{{ $user->id }}').submit(); };">
                                        <i class="fas fa-times text-danger"></i>
                                    </a>
                                    <form method="post" action="{{ route('admin.contestants.unassign.user', [$contestant->id, $user->id]) }}" id="unassign-{{ $user->id }}">
                                        @csrf
                                    </form>                                    
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    <hr>

                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#assign-users-modal">
                        Add <i class="fas fa-plus"></i>
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="assign-users-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel"> Assign contestant to user(s) </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="post" action="{{ route('admin.contestants.assign.users', $contestant) }}" id="assign-form">
                                        @csrf
                                        <table>
                                            @foreach( $users as $user )
                                                <tr> 
                                                    <td class="pr-3">
                                                        <input type="checkbox" name="users[]" value="{{ $user->id }}" id="check-{{ $user->id }}" required> 
                                                    </td>
                                                    <td class="pr-2">
                                                        <label for="check-{{ $user->id }}">{{ $user->last_name }}, </label>
                                                    </td>
                                                    <td class="pr-2">
                                                        <label for="check-{{ $user->id }}">{{ $user->first_name }} </label>
                                                    </td>
                                                    <td>
                                                        <label for="check-{{ $user->id }}">{{ $user->email }} </label>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" onclick="document.getElementById('assign-form').submit()">Assign</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


            <h2 class="my-0 font-weight-bold" style="font-size: 1.05em"> Rodeo entries </h2>
            <hr class="mt-2 mb-4">
            @if( $contestant->rodeos->count() < 1 )
                <p style="font-style: italic;">
                    Contestant is not entered into any rodeos...
                </p>
            @endif 

            @foreach( $contestant->rodeos as $rodeo )                    
                
                <div class="card mb-3">
                    <div class="card-header bg-white" onclick="document.getElementById('entry-card-{{ $rodeo->id }}').style.display = 'block'; document.getElementById('view-entry-icon-{{ $rodeo->id }}').style.display='none'; this.style.fontWeight='bold';return false;"> 
                        <div class="float-left">
                            {{ $rodeo->name ? $rodeo->name : "Rodeo #{$rodeo->id}" }} 
                            @if( $rodeo->starts_at  &&  $rodeo->ends_at )
                                <div class="d-block d-md-inline-block ml-md-4">
                                    <x-rodeo-date :date="$rodeo->starts_at" /> &ndash; <x-rodeo-date :date="$rodeo->ends_at" />
                                </div>
                            @endif
                        </div>
                        <div class="float-right" id="view-entry-icon-{{ $rodeo->id }}">
                            <a href="#" role="button" class="text-dark">
                                <i class="fas fa-plus"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-0" id="entry-card-{{ $rodeo->id }}" style="display: none;">

                        <table id="entries-table-{{ $rodeo->id }}" class="mb-md-2" style="width: 100%">
                            <tbody>
                                @foreach( $contestant->competition_entries->where('competition.rodeo_id', $rodeo->id)->sortBy('competition.event.name', SORT_NATURAL)->sortBy('competition.group.name', SORT_NATURAL) as $entry )
                                    <tr class="d-block px-3 py-1 border-top d-md-table-row border-md-none">
                                        <td class="d-block d-md-table-cell px-md-4 py-md-1"> {{ $entry->competition->group->name }} </td>
                                        <td class="d-block d-md-table-cell px-md-4 py-md-1"> {{ $entry->competition->event->name }} </td>
                                        <td class="d-block d-md-table-cell px-md-4 py-md-1"> @if( $entry->instance ) <x-rodeo-date :date="$entry->instance->starts_at" /> @endif </td>
                                        <td class="d-block d-md-table-cell px-md-4 py-md-1"> 
                                            @if( $entry->position )
                                                <span class="badge {{ in_array($entry->position, ['header', 'heeler']) ? "badge-outline-{$entry->position}" : ''}}">
                                                    {{ $entry->position }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            @endforeach

        </div>
    </div>

</div>
@endsection
