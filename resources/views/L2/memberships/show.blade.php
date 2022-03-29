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
    <hr>

    <div class="row">
        <div class="col-12 col-xl-6">

            <div class="card">
                <div class="card-body">

                    <div>
                        <div class="float-left font-weight-bold">
                            {{ $membership->contestant->lexical_name_order }}
                        </div>

                        <div class="text-right float-right">
                            <div class="dropdown">
                                <a hre="#" role="button" class="text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-h fa-lg"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">                
                                    <a class="dropdown-item" href="{{ route('L2.memberships.edit', [$organization, $series, $membership]) }}">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </a>                                
                                    <a class="dropdown-item" href="#" role="button" onclick="if(confirm('Are you sure you want to delete this membership?')) { document.getElementById('delete-membership-form').submit(); } return false;">
                                        <i class="fas fa-trash"></i>
                                        Delete
                                    </a>
                                    <form id="delete-membership-form" method="post" action="{{ route('L2.memberships.destroy', [$organization, $series, $membership]) }}">
                                        @method('delete')
                                        @csrf()
                                    </form>
                                </div>
                            </div>                            
                        </div> 
                        <div style="clear:both"></div>
                    </div>
                    <hr class="my-2"> 

          
                    Paid: 
                    @if($membership->paid) 
                        @if( $membership->payment  &&  $membership->payment->created_at )
                            {{ $membership->payment->created_at->toFormattedDateString() }}
                        @else
                            Yes
                        @endif
                    @else 
                        <small class="text-muted"> <i> Not paid... </i> </small> 
                    @endif

                </div><!--/card-body-->
            </div><!--/card-->

        </div>
    </div><!--/row-->

</div>
@endsection
