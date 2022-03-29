@extends('layouts.producer')

@section('content')
<div class="mt-n4 mx-n4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"> <a href="{{ route('L2.membership.home', [$organization->id]) }}"> Membership series </a> </li>
            <li class="breadcrumb-item active" aria-current="page"> Series #{{ $series->id }} </li>
        </ol>
    </nav>
</div>

<div class="container-fluid py-4">

    <x-session-alerts />

    <h1> Membership </h1>
    <hr class="mt-1 mb-3 mb-md-2">
    <div class="text-md-right mb-3">
        <a href="{{ route('L2.memberships.create', [$organization->id, $series->id]) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus pr-1"></i> 
            Add new member
        </a>
    </div>

    <h2 class="h-reset font-weight-bold"> Series </h2>
    <div class="card mb-5">
        <div class="card-body">
            
            {{ $series->name ? $series->name : 'Series #'.$series->id }} <br>
            @if( $series->starts_at )
                <x-rodeo-date :date="$series->starts_at" /> 

                @if( $series->ends_at )
                    &ndash;
                    <x-rodeo-date :date="$series->ends_at" /> 
                @endif
                <br>
            @endif

        </div>
    </div>

    <h2 class="h-reset font-weight-bold"> Members </h2>
    @if( $memberships->count() < 1 )
        <hr>
        <i> There no members for this series... </i>
    @else
        <table class="table bg-white border table-responsive-cards">
            <tbody> 
                @foreach( $memberships as $membership )
                    <tr>
                        <td> 
                            {{ $membership->contestant->lexical_name_order }} 
                        </td>
                        <td>
                            @if( !$membership->paid  ||  $membership->pending )
                                <span class="pending-member-badge"> PENDING MEMBER </span>
                            @else
                                <span class="member-badge"> MEMBER </span>
                            @endif
                        </td>
                        <td>
                            @if( !$membership->paid )
                                <small class="text-muted"> <i> unpaid </i> </small>
                            @endif
                        </td>
                        <td>                             
                            <a href="{{ route('L2.memberships.show', [$organization, $series, $membership]) }}" class="btn btn-outline-secondary btn-sm"> Details </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif



</div>
@endsection
