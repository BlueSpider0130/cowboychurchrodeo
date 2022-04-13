@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            @if(Gate::check('access-level-3-for-organization', $organization))
                <div class="px-2" style="font-size: 1rem; font-weight: bold;"> 
                    Organization staff
                </div>
                <ul class="list-group mb-4">
                    @if(Gate::check('access-level-2-for-organization', $organization))
                        <a href="{{ route('producer.home', $organization) }}" class="list-group-item list-group-item-action"> 
                            Secretary
                        </a>
                    @elseif(Gate::check('access-level-3-for-organization', $organization))
                        <a href="{{ route('producer.home', $organization) }}" class="list-group-item list-group-item-action"> 
                            Data entry / Check-in
                        </a>
                    @endif
                </ul>
            @endif
            

            <div class="px-2" style="font-size: 1rem; font-weight: bold;"> 
                Toolbox 
            </div>
            <ul class="list-group">

                <a href="{{ route('L4.contestants.index', $organization) }}" class="list-group-item list-group-item-action"> 
                    <i class="fas fa-hat-cowboy mr-1"></i>
                    Contestant{{ Auth::user()->contestants->count() > 1 ? 's' : '' }}
                </a>
{{--}}
                <a href="{{ route('L4.membership.home', $organization) }}" class="list-group-item list-group-item-action"> 
                    <i class="fas fa-address-card mr-1"></i>
                    Membership
                </a>
--}}
                <a href="{{ route('L4.registration.home', $organization) }}" class="list-group-item list-group-item-action"> 
                    <i class="far fa-list-alt mr-1"></i>
                    Registration
                </a>

                <a href="{{ route('L4.results.home', $organization) }}" class="list-group-item list-group-item-action" style="display: none;"> 
                    <i class="fas fa-trophy mr-1"></i>
                    Results
                </a>
{{--
                <a href="{ { route('L4.payment.home', $organization) }}" class="list-group-item list-group-item-action"> 
                    <i class="fas fa-money-check-alt mr-1"></i>
                    Payments
                </a>
--}}
            </ul>
            
          </div>
    </div>
</div>
@endsection
