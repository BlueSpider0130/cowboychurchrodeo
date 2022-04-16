@extends('layouts.app')

@section('content')
<div class="container">

    <x-session-alerts />
   
    <h1> Edit details </h1>
    <hr>

    <div class="card">
        <div class="card-body">

            <form method="post" action="{{ route('L4.contestants.update', [$organization, $contestant]) }}" enctype="multipart/form-data">
                @method('PUT')
                @csrf

                <div class="row mb-3">
                    <div class="col-12 my-0">
                        <label class="my-0"> Profile picture </label>
                        <hr class="mt-1 mb-2">
                    </div>
                    <div class="col-8 mx-auto mx-md-0 col-md-3 col-lg-2 text-center text-md-left">
                        <x-form.image-input 
                            id="profile_picture" 
                            name="profile_picture" 
                            value="{{ $contestant->photo_path ? asset('storage/'.$contestant->photo_path) : null }}" 
                            image-class="rounded"
                            class="text-center"
                        />
                    </div>
                </div>

                <div class="row mb-4 mb-md-3">
                    <div class="col-12 col-md-6">
                        <label for="first_name"> First name </label>
                        <x-form.input type="text" id="first_name" name="first_name" value="{{ $contestant->first_name }}" required />
                    </div>

                    <div class="col-12 col-md-6 mt-3 mt-md-0">
                        <label for="last_name"> Last name </label>
                        <x-form.input type="text" id="last_name" name="last_name" value="{{ $contestant->last_name }}" required />
                    </div>
                </div><!--/row-->     

                <div class="row mb-4 mb-md-3">
                    <div class="col-12 mb-2 mb-md-2">
                        <label for="address_line_1"> Address </label>
                        <x-form.input type="text" id="address_line_1" name="address_line_1" value="{{ $contestant->address_line_1 }}" />
                    </div>

                    <div class="col-12 col-md-6">
                        <x-form.input type="text" id="address_line_2" name="address_line_2" value="{{ $contestant->address_line_2 }}" />
                    </div>
                </div><!--/row-->

                <div class="row mb-4 mb-md-3">
                    <div class="col-12 col-md-6">
                        <label for="city"> City </label>
                        <x-form.input type="text" id="city" name="city" value="{{ $contestant->city }}" />
                    </div>

                    <div class="col-12 col-md-3 mt-3 mt-md-0">
                        <label for="state"> State </label>
                        <x-form.input type="text" id="state" name="state" value="{{ $contestant->state }}" />
                    </div>

                    <div class="col-12 col-md-3 mt-3 mt-md-0">
                        <label for="postcode"> Postcode </label>
                        <x-form.input type="text" id="postcode" name="postcode" value="{{ $contestant->postcode }}" />
                    </div>
                </div><!--/row--> 

                <div class="row mb-4 md-3">
                    <div class="col-12">
                        <label for="birthdate"> Birthdate </label>
                        <x-form.input type="date" id="birthdate" name="birthdate" value="{{ $contestant->birthdate ? $contestant->birthdate->format('Y-m-d') : null }}" />
                    </div>
                </div><!--/row-->

                <div class="row mb-4 md-3">
                    <div class="col-12">
                        <label for="gender"> Gender </label>
                        <x-form.select name="sex" id="sex" :options="['male' => 'male', 'female' => 'female']" value="{{ $contestant->sex }}">
                        </x-form.select>
                    </div>
                </div><!--/row-->

                <div class="row mb-4 md-3">
                    <div class="col-12">
                        <label for="phone"> Contact Phone Number (5555555555 10 digits only) </label>
                        <x-form.input type="text" id="phone" name="phone" value="{{ $contestant->phone }}"/>
                    </div>
                </div><!--/row-->

                <hr>

                <x-form.buttons submit-name="Update" :cancel-url="route('L4.contestants.index', $organization)" />

            </form>

        </div><!--/card-body-->
    </div><!--/card-->

</div>
@endsection

