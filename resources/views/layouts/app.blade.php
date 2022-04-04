<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Rodeo management application.">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Rodeo App') }}</title>

    <!-- Scripts -->
<script type="text/javascript" src="https://js.squareupsandbox.com/v2/paymentform"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset('css/square.css') }}" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/payment.css') }}" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>


    @stack('head')
</head>
<body>
    <x-operator-bar />
    <div id="app">

        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm" id="navbar-top">
            <div class="container-fluid">         
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Rodeo App') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav ml-md-3 mr-auto">
                        @if( Request::route('organization') )
                            <li class="nav-item">

                                <div class="d-md-none mt-2 pt-3 border-top"> 
                                    {{ Request::route('organization')->name }}
                                    <hr class="my-2">
                                </div>

                                <a class="nav-link" href="{{ route('organizations.show', Request::route('organization')->id) }}">
                                    <span class="d-none d-md-inline">
                                        {{ Request::route('organization')->name }}
                                    </span>
                                    <span class="d-md-none">
                                        <i class="fas fa-home fa-icon"></i>
                                        Homepage
                                    </span>                                    
                                </a>
                            </li>
                        @endif
                    </ul>

                    <div class="ml-auto">
                        @stack('top-navbar-right-side-start')
                    </div>
                    <ul class="navbar-nav ml-md-4">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else    
                            @include( 'layouts/_navbar_main_user_dropdown' )                      
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        @section('main')
            <main role="main" class="py-4">
                @yield('content')
            </main>
        @show
        
        @include('partials.feedback_footer')
    </div><!--/app-->

    @stack('body')
</body>
<script>
    // $('#success').hide();

    const paymentForm = new SqPaymentForm({
        // Initialize the payment form elements

        //TODO: Replace with your sandbox application ID
        applicationId: "sandbox-sq0idb-GAIKxUpEwpJQxZF5qq8quA",
        inputClass: 'sq-input',
        autoBuild: false,
        // Customize the CSS for SqPaymentForm iframe elements
        inputStyles: [{
            fontSize: '16px',
            lineHeight: '24px',
            padding: '16px',
            placeholderColor: '#a0a0a0',
            backgroundColor: 'transparent'
        }],

        cardNumber: {
            elementId: 'sq-card-number',
            placeholder: 'Card Number'
        },
        cvv: {
            elementId: 'sq-cvv',
            placeholder: 'CVV'
        },
        expirationDate: {
            elementId: 'sq-expiration-date',
            placeholder: 'MM/YY'
        },
        postalCode: {
            elementId: 'sq-postal-code',
            placeholder: 'Postal'
        },
        // SqPaymentForm callback functions
        callbacks: {
            /*
             * callback function: cardNonceResponseReceived
             * Triggered when: SqPaymentForm completes a card nonce request
             */
            cardNonceResponseReceived: function(errors, nonce, cardData) {
                if (errors) {
                    // Log errors from nonce generation to the browser developer console.
                    console.error('Encountered errors:');
                    errors.forEach(function(error) {
                        console.log('what is bug?');
                        console.error('  ' + error.message);
                    });
                    // alert('Encountered errors, check browser developer console for more details');
                    return;
                }
                //TODO: Replace alert with code in step 2.1
                // const idempotency_key = uuidv4();

                //  alert('here is your card token ' + nonce);
                const pay_amount = $("#pay_val").val();
                const tax = pay_amount * 0.05;
                const contestant_id = $("#contestant_id").val();
                const rodeo = $("#rodeo").val();
                const payer_user_name = $("#payer_user_name").val();
                const payer_user_email = $("#payer_user_email").val();
                const contestant_name = $("#contestant_name").val();
                const competition_entry_id = $("#competition_entries_id").val();
                console.log(contestant_id);
                // $('#success').hide();
                $("#sq-creditcard").prop('disabled', true);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('addCard') }}",
                    type: "POST",
                    data: {
                        'nonce':nonce,
                        'pay_amount':pay_amount,
                        'contestant_id':contestant_id,
                        'payer_user_name':payer_user_name,
                        'payer_user_email': payer_user_email
                    },
                    success: function(data) {
                        // $('#success').show();
                        console.log('data', data);
                        // console.log(success);
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: "{{ route('L4.addPaymentTable') }}",
                            type: "POST",
                            data: {
                                'amount':pay_amount,
                                'tax':tax,
                                'contestant_id':contestant_id,
                                'rodeo' : rodeo,
                                'payer_user_name' : payer_user_name,
                                'contestant_name' : contestant_name,
                                'competition_entry_id' : competition_entry_id
                            },
                            success: function(data) {
                                console.log("success");
                                $('#staticBackdrop').modal('show');
                            },
                            error:function(xhrm, status, error){
                                console.log("error", error);
                                $('#staticBackdrop').modal('show');
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.log('error', error);
                        $("#staticBackdroperr").modal('show');
                    }
                });
            }
        }
    });
    paymentForm.build();

    // onGetCardNonce is triggered when the "Pay $1.00" button is clicked
    function onGetCardNonce(event) {
        // Don't submit the form until SqPaymentForm returns with a nonce
        event.preventDefault();
        // Request a nonce from the SqPaymentForm object
        paymentForm.requestCardNonce();
    }
</script>

</html>
