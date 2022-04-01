<!-- link to the SqPaymentForm library -->
@extends('layouts.app')

@section('content')

<div class="center">

    <!-- <x-session-alerts /> -->
    <div class=" row" id="card_con">
        <div class="col-lg-5 col-md-6 col-sm-12 pay-detail-con">
            <a href="{{ URL::previous() }}"><button class="back-btn">back</button></a>
            <div class="row check-title">
                <p>Checkout</p>
            </div>
            <div class="row full-name">
                <p>{{ $contestant->last_name }}, {{ $contestant->first_name }}</p>
            </div>
            <div class="scroll-con">
                @foreach ($payData as $price)
                <div class="row detail-layer">
                    <div class="col-3 fee-dollar">
                        <p>${{ $price -> entry_fee }}</p>
                    </div>
                    <div class="col-9 rodeo-name">
                        <p>{{ $price -> name }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="row off-fee">
                <p>Total fee</p>
            </div>
            <div class="row fee-dollar" id="office">
                <p>${{$payAmount * 0.05}}</p>
            </div>
            <div class="row off-fee">
                <p>Total</p>
            </div>
            <div class="row fee-dollar" id="total_amount">
                <p>${{ $payAmount * 1.05 }}</p>
                <input type="hidden" id="pay_val" value="{{$payAmount}}" />
                <input type="hidden" id="contestant_id" value="{{$contestant->id}}" />
                <input type="hidden" id="rodeo" value="{{$rodeo->id}}" />
                <input type="hidden" id="payer_user_name" value="{{$payer_user_name}}" />
                <input type="hidden" id="payer_user_email" value="{{$payer_user_email}}" />
                <input type="hidden" id="contestant_name" value="{{$contestant -> name}}" />
            </div>
        </div>

        <div class="col-lg-7 col-md-6 col-sm-12 pay-api-con">
            <div class="row check-title">
                <p>Payment</p>
            </div>
            <div class="row image-con">
                <img class="visa" src="/assets/card.png" />
            </div>
            <!-- ---------------------form api con -->
            <div id="form-container" class="sq-payment-form">
                <div class="sq-field">
                    <div id="sq-card-number"></div>
                </div>
                <div class="sq-field-wrapper">
                    <div class="sq-field sq-field--in-wrapper">
                        <div id="sq-cvv"></div>
                    </div>
                    <div class="sq-field sq-field--in-wrapper">
                        <div id="sq-expiration-date"></div>
                    </div>
                    <div class="sq-field sq-field--in-wrapper">
                        <div id="sq-postal-code"></div>
                    </div>
                </div>
            </div>
            <div class="row pay-btn-con">
                <button id="sq-creditcard" class="button-credit-card" onclick="onGetCardNonce(event)">PURCHASE</button>
                <!-- {{json_encode($organization)}}<br>organization -->
                <!-- {{json_encode($rodeo)}}<br>rodeo -->
                <!-- {{json_encode($contestant)}}<br>contestant -->
                <!-- {{json_encode($sortedCompetitions)}}<br>sortedCompetitions -->
                <!-- {{json_encode($payData)}}<br>payData -->
                <!-- {{json_encode($payAmount)}}<br>payAmount -->
                <!-- {{json_encode($competitionEntries)}}<br>competitionEntries -->
            </div>
        </div>
    </div>

    <!-- ------------------------------payment form container start------------ -->


</div>
<div class="modal fade modal-side modal-top-right" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Your Payment Request Success!</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"> -->
                    <!-- <span aria-hidden="true">&times;</span> -->
                <!-- </button> -->
            </div>
            <div class="modal-body">
                <i class="far fa-check-circle"></i>Go to Confirm Page.<i class="far fa-check-circle"></i>
            </div>
            <div class="modal-footer">
                <form method="post" action="{{ route('L4.registration.save', [$organization->id, $rodeo->id, $contestant->id] ) }}">
                    @csrf()
                    <button class="btn btn-primary"> Done! </button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- -----------------error modal -->
<div class="modal fade modal-side modal-top-right" id="staticBackdroperr" data-backdrop="" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabelerr" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabelerr">Please add correct detail!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <i class="far fa-check-circle"></i><i class="far fa-check-circle"></i>Did you input correct detail of card?
            </div>
            <div class="modal-footer">
                
            </div>
        </div>
    </div>
</div>
@endsection