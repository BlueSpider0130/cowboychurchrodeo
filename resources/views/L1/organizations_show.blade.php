@extends('layouts.admin')

@section('content')

<nav class="admin-breadcrumbs" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"> <a href="{{ route('admin.organizations.index') }}"> Organizations </a> </li>
        <li class="breadcrumb-item active" aria-current="page"> #{{ $organization->id }} </li>
    </ol>
</nav>


<div class="container-fluid py-4">

   <x-session-alerts />
    
    <h1> {{ $organization->name }} </h1>
    <hr>
    <div class="row">
        <div class="col text-secondary">
            <table class="text-secondary" style="font-size: .85rem">

                <tr> <td class="pr-3"> Address: </td> <td> {{ $organization->address_line_1 }} </td> </tr>

                @if( $organization->address_line_1  &&  $organization->address_line_2 )
                    <tr> <td class="pr-3"> </td> <td> {{ $organization->address_line_2 }} </td> </tr>
                @endif

                <tr> <td class="pr-3"> City: </td> <td> {{ $organization->city }} </td> </tr>
                <tr> <td class="pr-3"> State: </td> <td> {{ $organization->state }} </td> </tr>
                <tr> <td class="pr-3"> Postcode: </td> <td> {{ $organization->postcode }} </td> </tr>
                <tr> <td class="pr-3"> Phone: </td> <td> {{ $organization->phone }} </td> </tr>
                <tr> <td class="pr-3"> Email: </td> <td> {{ $organization->email }} </td> </tr>

            </table>
            
            @if( $organization->admin_notes )
                <div class="mt-4">
                    <div> Notes: </div>                    
                    <div class="d-inline-block border-top my-1 py-1">{{ $organization->admin_notes }}</div>
                </div>
            @endif
        </div>

        <div class="col-auto">
            <x-resource.dropdown-menu
                :edit-url="route('admin.organizations.edit', $organization->id)" 
                :delete-url="route('admin.organizations.destroy', $organization->id)"
                delete-message="Are you sure you want to delete this organization?"
            > 
                <x-slot name="icon">
                    <i class="fas fa-cog fa-lg"></i>
                </x-slot>                
            </x-resource.dropdown-menu>
        </div>
    </div>

    <hr>

    <h2 class="mt-5"> Level 2 users </h2>
    <div class="mb-5">
        @if( $organization->user_levels->where('level', 2)->count() > 0 )
            <table class="table"> 
                @foreach( $organization->user_levels->where('level', 2) as $userLevel )
                    <tr> <td> {{ $userLevel->user->name }} </td> </tr>
                @endforeach
            </table>
        @endif
        <hr class="my-2">
        <a 
            href="{{ route('admin.organization.user.level.index', ['organization' => $organization, 'level' => 2]) }}" 
            class="btn btn-outline-primary btn-sm"
        > Manage users </a>
    </div>

    <h2 class="mt-5"> Level 3 users </h2>
    <div class="mb-5">
        @if( $organization->user_levels->where('level', 3)->count() > 0 )
            <table class="table"> 
                @foreach( $organization->user_levels->where('level', 3) as $userLevel )
                    <tr> <td> {{ $userLevel->user->name }} </td> </tr>
                @endforeach
            </table>            
        @endif        
        <hr class="my-2">
        <a 
            href="{{ route('admin.organization.user.level.index', ['organization' => $organization, 'level' => 3]) }}" 
            class="btn btn-outline-primary btn-sm"
        > Manage users </a>
    </div>

</div>
@endsection

