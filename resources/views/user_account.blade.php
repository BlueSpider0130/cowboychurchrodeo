@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">           

            <x-session-alerts />

            <h1 style="font-size: 1.5rem"> Account settings </h1>
            <hr>

            <div class="mt-2 mb-5">
                <b> Change details </b>
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('account.update.info') }}">
                            @method('PATCH')
                            @csrf 

                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <label for="first_name">First name</label>
                                    <x-form.input class="mb-1" id="first_name" type="text" name="first_name" value="{{ old('first_name', Auth::user()->first_name ) }}" required /> 
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="last_name">Last name</label>
                                    <x-form.input class="mb-1" id="last_name" type="text" name="last_name" value="{{ old('last_name', Auth::user()->last_name ) }}" required /> 
                                </div>
                            </div>

                            <x-form.buttons submit-name="Update" />

                        </form>                
                    </div>
                </div><!--/card-->
            </div>

            <div class="mb-5">
                <b> Change email </b>
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('account.update.email') }}">
                            @method('PATCH')
                            @csrf 

                            <label for="email">New email</label> 
                            <x-form.input class="mb-1" id="email" type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required /> 

                            <x-form.buttons submit-name="Change email" />
                        </form>
                    </div>
                </div><!--/card-->
            </div>


            <div class="mb-5">
                <b> Change password </b>
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('account.update.password') }}">
                            @method('PATCH')
                            @csrf 

                            <label for="password">New password</label>
                            <x-form.input class="mb-1" id="password" type="password" name="password" required /> 

                            <label for="password_confirmation"> Confirm password</label>
                            <x-form.input class="mb-1" id="password_confirmation" type="password" name="password_confirmation" required /> 

                            <x-form.buttons submit-name="Change password" />
                        </form>                
                    </div>
                </div><!--/card--> 
            </div>

            

        </div>
    </div>
</div>
@endsection
