@extends('layouts.app')

@section('content')
<div class="container">
   
    <div class="mb-4">
        <a href="{{ route('toolbox', $organization->id) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-chevron-left"></i>
            Toolbox
        </a>        
    </div> 

    <x-session-alerts />
   
    <h1> Contestant{{ $contestants->count() > 1 ? 's' : ''}} </h1>
    <hr>

    @if( $contestants->count() < 1 )
        <p>
            You must create a contestant account to participate in rodeo events.
        </p>

        <p>
            You can also create contestant accounts for your children / dependents and manage them here also.
        </p>
        <hr>
    @endif 

    @if( $contestants->count() > 0 )
        <div class="mb-5">
            @foreach( $contestants as $contestant )

                <div class="card mb-2">
                    <div class="card-body">                        
                        <div class="row">

                            <!-- contestant photo -->
                            <div class="col-6 mb-3 order-first col-md-3 mb-md-0 order-md-first">
                                @if($contestant->photo_path)
                                    <img src="{{ asset('storage/'.$contestant->photo_path) }}" class="rounded" width="100%"> <br>
                                @else
                                    <div class="rounded" style="background-color: #ccc; width: 100%; padding-top: 75%; position: relative;">
                                        <div style="text-align: center; position: absolute; top:40%; left: 0; bottom: 0; right: 0;"> <i> no photo </i> </div>
                                    </div>
                                @endif
                            </div>

                            <!-- contestant info -->
                            <div class="col-12 order-last col-md-8 order-md-2">
                                        
                                        {{ $contestant->name }}<br>

                                        @if( $contestant->birthdate )
                                            <b>Birthdate: </b> {{ $contestant->birthdate ? $contestant->birthdate->toFormattedDateString() : '' }} <br>
                                        @endif
                                        <br>

                                        @if($contestant->address_line_1 || $contestant->city || $contestant->state || $contestant->postcode)
                                            <b>Address: </b> <br>
                                            @if($contestant->address_line_1)
                                                {{ $contestant->address_line_1 }}<br>
                                            @endif

                                            @if($contestant->address_line_2)
                                                {{ $contestant->address_line_2 }}<br>
                                            @endif
                                            
                                            @if($contestant->city)
                                                {{ $contestant->city }}, 
                                            @endif
                                            @if($contestant->state)
                                                {{ $contestant->state }} 
                                            @endif
                                            @if($contestant->postcode) 
                                                {{ $contestant->postcode }}
                                            @endif                    
                                            @if($contestant->city || $contestant->state || $contestant->postcode)
                                                <br>
                                            @endif
                                            <br>
                                        @endif     
<?php
/*
                                        <a href="#{{-- route('L4.registration.rodeos', [$organization, $contestant]) --}}" class="btn btn-outline-primary btn-sm">
                                            Register for rodeos
                                        </a>
                                        &nbsp;
                                        <a href="#{{-- route('L4.registration.rodeos', [$organization, $contestant]) --}}" class="btn btn-outline-primary btn-sm">
                                            View rodeo entries
                                        </a>
*/
?>                                                         
                            </div><!--/col-->

                            <!-- contestant settings -->
                            <div class="col col-md order-md-last text-right">
                                <button class="btn-reset" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-cog fa-lg text-secondary"></i>
                                </button>

                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item"  href="{{ route('L4.contestants.edit', [$organization, $contestant]) }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-edit pr-2"></i> Edit
                                    </a>
                                    <button class="dropdown-item" type="button" onclick="if(confirm('Are you sure you want to delete this contestant?')){ document.getElementById('delete-contestant-{{ $contestant->id }}').submit(); }"> 
                                        <i class="fas fa-trash pr-2"></i> Delete 
                                    </button>
                                    <form id="delete-contestant-{{ $contestant->id }}" method="post" action="{{ route('L4.contestants.destroy', [$organization, $contestant]) }}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        @csrf()
                                    </form>
                                </div>
                            </div><!--/col-->

                        </div><!--/row-->
                    </div><!--/card-body-->
                </div><!--/card-->
                 
            @endforeach   
        </div>
    @endif

    <p>
        <a href="{{ route('L4.contestants.create', [$organization]) }}" class="btn btn btn-primary">
            <i class="fas fa-plus pr-1"></i> 
            Add contestant 
        </a>
    </p>    

</div>
@endsection

