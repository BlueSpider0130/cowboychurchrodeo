@extends('layouts.admin')

@section('content')

<nav class="admin-breadcrumbs" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"> <a href="{{ route('admin.users.index') }}"> Users </a> </li>
        <li class="breadcrumb-item active" aria-current="page"> #{{ $user->id }} </li>
    </ol>
</nav>

<div class="container-fluid py-4">

    <x-session-alerts />
    
    <h1> {{ $user->getName() ? $user->getName() : "#{$user->id}" }} </h1>
    <hr>

    <div class="row">
        <div class="col">
            <table class="mb-4">
                <tr>
                    <td style="font-weight: bold; padding-right: 1rem"> First name </td>
                    <td> {{ $user->first_name }} </td>
                </tr>
                <tr>
                    <td style="font-weight: bold; padding-right: 1rem"> Last name </td>
                    <td> {{ $user->last_name }} </td>
                </tr>
                <tr>
                    <td style="font-weight: bold; padding-right: 1rem"> Email </td>
                    <td> {{ $user->email }} </td>
                </tr>
                <tr>
                    <td style="font-weight: bold; padding-right: 1rem"> Created </td>
                    <td> {{ $user->created_at->toDayDateTimeString() }} </td>
                </tr>
                @if( $user->last_login_at )
                    <tr>
                        <td style="font-weight: bold; padding-right: 1rem"> Last login </td>
                        <td> {{ $user->last_login_at->toDayDateTimeString() }} </td>
                    </tr>
                @endif
                @if( $user->isAdmin() )
                    <tr>
                        <td style="font-weight: bold; padding-right: 1rem"> Admin </td>
                        <td> <span class="text-success">&#10003;</span> </td>
                    </tr>
                @endif

            </table>
        </div>
    
        <div class="col-auto">
            <x-resource.dropdown-menu
                :edit-url="route('admin.users.edit', $user->id)" 
                :delete-url="route('admin.users.destroy', $user->id)"
                delete-message="Are you sure you want to delete this user?"
            > 
                <x-slot name="icon">
                    <i class="fas fa-cog fa-lg"></i>
                </x-slot>                
            </x-resource.dropdown-menu>
        </div>

    </div>            

    <hr class="mb-5">

    <h2> Contestantants </h2>    
    @if( $user->contestants->count() < 1 )
        <hr class="my-2">
        <small class="text-muted"><i>User does not have any contestants assigned </i></small>
    @else
        <table class="table">
            <thead>
                <th>First name</th>
                <th>Last name</th>               
                <th>Organization</th>
            </thead>
            <tbody>
                @foreach( $user->contestants->sortBy('first_name') as $contestant )
                    <tr>                       
                        <td>{{ $contestant->first_name }}</td>
                        <td>{{ $contestant->last_name }}</td>
                        <td>{{ $contestant->organization->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</div>
@endsection

