@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <x-session-alerts />
    
    <div class="row mb-4 mb-md-1">
        <div class="col-12 col-md">
            <h1> Organizations </h1>
        </div>
        <div class="col-12 col-md text-md-right">
            <hr class="mt-0 mb-2 d-md-none">
            <a href="{{ route('admin.organizations.create') }}" class="btn btn-primary btn-sm"> 
                Create new organization 
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
                        @if( in_array('name', $sortable) )
                            <x-list-builder.sort-by-table-header sort-by="name"> Name </x-list-builder.sort-by-table-header>
                        @else
                            Name 
                        @endif
                    </th>
                    <th class="text-nowrap"> 
                        @if( in_array('created_at', $sortable) )
                            <x-list-builder.sort-by-table-header sort-by="created_at"> Created at </x-list-builder.sort-by-table-header>
                        @else
                            Created at
                        @endif
                    </th>                
                    <th class="text-center"> </th>
                </tr>              
            </thead>
            <tbody>
                @foreach($results as $record)
                    <tr>
                        <td> {{ $record->name }} </td>                        
                        <td> {{ $record->created_at->toDayDateTimeString() }} </td>
                        <td class=""> 
                            <a 
                                href="{{ route('admin.organizations.show', $record->id) }}" 
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
@endsection
