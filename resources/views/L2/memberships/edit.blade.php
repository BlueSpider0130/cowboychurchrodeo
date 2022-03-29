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

    <div class="card">
        <div class="card-body">

            <div class="mb-4">
                {{ $membership->contestant->lexical_name_order }}
            </div>


            <form method="post" action="{{ route('L2.memberships.update', [$organization->id, $series->id, $membership->id]) }}">
                @method('patch')
                @csrf

                <div class="mb-2">
                    <div class="form-check @error('paid') is-invalid @enderror">
                        <input 
                            class="form-check-input" 
                            type="checkbox" 
                            name="paid"                            
                            id="paid-checkbox"
                            @if( old('paid', ($membership->paid ? true : false)) ) checked @endif
                        >
                        <label class="form-check-label" for="paid-checkbox">
                            Paid <small class="text-muted d-inline-block ml-2"> * If contestant has already paid for membership. </small>
                        </label>
                    </div>
                    @error('paid')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>                
                    @enderror
                </div>

                <hr>

                <x-form.buttons submit-name="Save" :cancel-url="route('L2.memberships.show', [$organization->id, $series->id, $membership->id])" />

            </form>

        </div><!--/card-body-->
    </div><!--/card-->


</div>
@endsection
