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

    <h1> Add new member </h1>
    <hr>

    <div class="card">
        <div class="card-body">

            <form method="post" action="{{ route('L2.memberships.store', [$organization->id, $series->id]) }}">
                @csrf

                <div class="mb-4">
                    <label> Contestants </label>
                    @php
                        $options = [];
                        foreach( $contestants as $contestant )
                        {
                            $options[$contestant->id] = $contestant->lexical_name_order;
                        }
                    @endphp
                    <x-form.select name="contestant" :options="$options" />
                </div>

                <div class="mb-2">
                    <div class="form-check @error('paid') is-invalid @enderror">
                        <input 
                            class="form-check-input" 
                            type="checkbox" 
                            name="paid"                            
                            id="paid-checkbox"
                            @if( old('paid') ) checked @endif
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

                <x-form.buttons submit-name="Add" :cancel-url="route('L2.memberships.index', [$organization->id, $series->id])" />

            </form>

        </div><!--/card-body-->
    </div><!--/card-->


</div>
@endsection
