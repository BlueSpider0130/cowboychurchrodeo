@extends('layouts.producer')

@section('content')
<div class="mt-n4 mx-n4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"> <a href="{{ route('L2.contestants.index', [$organization->id]) }}"> Contestants </a> </li>
            <li class="breadcrumb-item active" aria-current="page"> {{ $contestant->lexical_name_order }} </li>
        </ol>
    </nav>
</div>

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
                            @if( $contestant->sex )
                                <p>
                                    <span class="mr-2 font-weight-bold"> Gender: </span> 
                                    {{ $contestant->sex }}
                                    <img src="/assets/{{$contestant->sex}}.png">
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
                            @if( $contestant->phone )
                                <p>
                                    <span class="mr-2 font-weight-bold"> Phone Number: </span> 
                                    {{ $contestant->phone}}
                                </p>
                            @endif
                        </div>

                        <div class="col-12 col-md text-md-right">
                            <hr class="mt-0 mb-2 d-md-none">
                            
                            <a href="{{ route('L2.contestants.edit', [$organization, $contestant]) }}" class=" btn btn-outline-secondary btn-sm"> 
                                <i class="fas fa-edit"></i>
                                Edit 
                            </a>

                            <x-delete-button 
                                url="{{ route('L2.contestants.destroy', [$organization, $contestant]) }}" 
                                message="Are you sure you want to delete this contestant?"
                                class="btn btn-outline-danger btn-sm"
                            >
                                <i class="fas fa-trash"></i> Delete
                            </x-delete-button>
                        </div>

                    </div><!--/row-->

                    <div class="row">
                        <div class="col">
                            User account: 
                            @foreach( $contestant->users as $user )
                                {{ $user->email }} <br>
                                @endforeach
                        </div>
                    </div>

                </div>
            </div><!--/card-->



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

                        <div class="px-3 pt-2 text-right">
                            <div class="dropdown">
                                <a hre="#" role="button" class="text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-h fa-lg"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">                
                                    <a class="dropdown-item" href="{{ route('L2.registration.show', [$organization, $rodeo, $contestant]) }}">
                                        <i class="fas fa-edit"></i>
                                        Edit registration
                                    </a>                                
                                </div>
                            </div>
                        </div> 

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


            <!-- Registration -->
            <h2 class="my-0 mt-5 font-weight-bold" style="font-size: 1.05em"> Register </h2>
            <hr class="mt-2 mb-4">
            @if( $currentRodeos->count() < 1 )
                <div class="card mt-3">   
                    <div class="card-body">                        
                        <p style="font-style: italic;">
                            There are no rodeos or all rodeos have ended... 
                        </p>
                    </div>
                </div>
            @else
                <table class="table bg-white border mt-3">

                    <tbody>
                        @foreach( $currentRodeos as $rodeo )
                            <tr class="d-block d-md-table-row">
                                <td class="d-block d-md-table-cell">
                                    {{ $rodeo->name }} <br>
                                    <x-rodeo-date :date="$rodeo->starts_at" /> &ndash; <x-rodeo-date :date="$rodeo->ends_at" />
                                </td>
                                <td class="d-block d-md-table-cell text-md-center" valign="middle">
                                    <a 
                                        href="{{ route('L2.registration.show', [$organization->id, $rodeo->id, $contestant->id]) }}" 
                                        class="btn btn-outline-primary btn-sm"
                                    >
                                        Registration
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>                    
                </table>
            @endif

        </div>
    </div>

</div>
@endsection
