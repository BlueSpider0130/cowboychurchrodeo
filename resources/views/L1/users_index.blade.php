@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <x-session-alerts />

    <div class="row mb-4 mb-md-1">
        <div class="col-12 col-md">
            <h1> Users </h1>
        </div>
        <div class="col-12 col-md text-md-right">
            <hr class="mt-0 mb-2 d-md-none">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm"> 
                Create new user 
            </a>
        </div>
    </div>
    <hr class="mt-1 d-none d-md-block">

    @include('L1.partials.index_search')

    @if( $results->count() < 1 )
        <hr>
        <p> <i> No results... </i> </p>
    @else    
                
        @include('L1.partials.index_results_per_page')

        <table class="table table-striped table-responsive-md">
            <thead style="font-weight: bold">
                <tr>
                    <th class="text-nowrap"> 
                        @if( in_array('id', $sortable) )
                            <x-list-builder.sort-by-table-header sort-by="id"> ID </x-list-builder.sort-by-table-header>
                        @else
                            ID
                        @endif
                    </th>

                    <th class="text-nowrap"> 
                        @if( in_array('last_name', $sortable) )
                            <x-list-builder.sort-by-table-header sort-by="last_name"> Last name </x-list-builder.sort-by-table-header>
                        @else
                            Last name
                        @endif
                    </th>

                    <th class="text-nowrap"> 
                        @if( in_array('first_name', $sortable) )
                            <x-list-builder.sort-by-table-header sort-by="first_name"> First name </x-list-builder.sort-by-table-header>
                        @else
                            First name 
                        @endif
                    </th>

                    <th class="text-nowrap"> 
                        @if( in_array('email', $sortable) )
                            <x-list-builder.sort-by-table-header sort-by="email"> Email </x-list-builder.sort-by-table-header>
                        @else
                            Email
                        @endif
                    </th>

                    <th class="text-nowrap"> 
                        @if( in_array('created_at', $sortable) )
                            <x-list-builder.sort-by-table-header sort-by="created_at"> Created </x-list-builder.sort-by-table-header>
                        @else
                            Created
                        @endif
                    </th>

                    <td class="text-nowrap text-center"> 
                        Admin 
                    </td>

                    <td class="text-center"> 
                        Login as 
                    </td>

                    <td> </td>
                </tr>
            </thead>
            <tbody>
                @foreach($results as $record)
                    <tr>
                        <td> #{{ $record->id }} </td>
                        <td> {{ $record->last_name }} </td>
                        <td> {{ $record->first_name }} </td>                        
                        <td> {{ $record->email }} </td>
                        <td> {{ $record->created_at->toDayDateTimeString() }} </td>
                        <td class="text-center">
                            @if( $record->isAdmin() )
                                <i class="fas fa-check"></i>
                            @endif
                        <td class="text-center">  
                            @if( $record->id != Auth::user()->id )
                                <a href="{{ route('admin.user.operator.start', $record->id) }}">
                                    <i class="fas fa-random"></i> 
                                </a>
                            @endif
                        </td>
                        <td class="text-right"> 
                            <a 
                                href="{{ route( 'admin.users.show', $record->id) }}" 
                                class="btn btn-outline-primary btn-sm"
                            >                                 
                                Details
                            </a> 
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @include('L1.partials.index_links')

    @endif
    </div>


</div>
@endsection
