@extends('layouts.admin')

@section('content')

<nav class="admin-breadcrumbs" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"> <a href="{{ route('admin.organizations.index') }}"> Organizations </a> </li>
        <li class="breadcrumb-item"> <a href="{{ route('admin.organizations.show', $organization->id) }}"> #{{ $organization->id }} </a> </li>
        <li class="breadcrumb-item active" aria-current="page"> Level {{ $level }} users </li>
    </ol>
</nav>

<div class="container-fluid py-4">

    <x-session-alerts />
    
    <h1> 
        Level {{ $level }} users 
        @if( 2 == $level ) 
            - secretary  
        @elseif( 3 == $level )
            - data entry
        @endif
    </h1>
    <hr>
    <div class="text-secondary mb-4">
        Organization: {{ $organization->name }}
    </div>

    @if( $userLevels->count() > 0 )
        <table class="table table-striped">
            <thead style="font-weight: bold">
                <tr>
                    <td> User </td>
                    <td> Email </td>
                    <td class="text-center"> Active </td>
                    <td> Updated </td>
                    <td class="text-center"> </td>
                </tr>
            </thead>
            <tbody>
                @foreach( $userLevels as $userLevel )
                    <tr> 
                        <td> {{ $userLevel->user->getName() }} </td>
                        <td> {{ $userLevel->user->email }} </td>
                        <td class="text-center">
                            @if( $userLevel->active )
                                <i class="fas fa-check text-success font-weight-bold"></i>
                            @else
                                <span class="text-danger" style="font-style: italic;">disabled</span>
                            @endif
                        </td>
                        <td>
                            {{ $userLevel->updated_at->format('Y-m-d H:i:s') }}
                        </td>
                        <td class="text-center">
                            <x-delete-button 
                                :url="route('admin.organization.user.level.destroy', [$organization->id, $userLevel->level, $userLevel->user_id])" 
                                message="Are you sure you want to remove this user from Level {{ $level }}?"
                            >
                                <i class="far fa-times-circle fa-lg text-danger"></i>
                            </x-confirm-delete>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="mt-5">
        <h2> Add user </h2>
        <hr>
        <div class="row">
            <div class="col col-md-6">

                @if( isset($availableUsers)  &&  $availableUsers )
                    ...
                    <div class="my-3">
                        <b> &ndash; or &ndash; </b>
                    </div>
                @endif

                <form method="post" action="{{ route('admin.organization.user.level.store', [$organization->id, $level]) }}">
                    @csrf
                    <div class="input-group @error('email') is-invalid @enderror">
                        <x-form.input 
                            :with-error="false"     
                            type="email" 
                            name="email" 
                            placeholder="xxxx@xxxx.xxxx" 
                            class="form-control"
                            required
                        />
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary" type="button"> 
                                <i class="fas fa-plus pr-1"></i> Add 
                            </button>
                        </div>
                    </div>                                                                
                    <x-form.error name="email" />
                </form>

            </div>
        </div><!--/row-->
      
    </div>

</div>
@endsection

