@extends('layouts.producer')

@section('content')
<div class="container-fluid">
    
    @php
        $eventCount = $organization->events()->count();
        $groupCount = $organization->groups()->count();
    @endphp
    @if( $eventCount < 1 ||  $groupCount < 1 )
        <div class="row mb-5">
            <div class="col col-md-9">
                <div class="card">
                    <div class="card-body">
                        <b> Organization setup steps </b>
                        <hr class="my-2">
                        <p> You need to create rodeo events and groups for your organization in the organization "settings" section. </p>
                        <ol>
                            <li  @if( $eventCount > 0 ) class="text-secondary" style="text-decoration: line-through;" @endif> 
                                Create rodeo events (example: Barrel racing, Roping, etc.) 
                            </li>
                            <li  @if( $groupCount > 0 ) class="text-secondary" style="text-decoration: line-through;" @endif> 
                                Create groups (example: 10 & under, 18 and over, etc.)
                            </li>
                        </ol>
                    </div>
                </div>
            </div><!--/col-->
        </div><!--/row-->
    @endif

    <x-session-alerts />

    <h1>{{ $organization->name }}</h1>
    <hr>

    <div class="row">
        <div class="col col-md-9">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"> Summary </h5>
                    <p class="card-text">
                        <table class="table">
                            <tr>
                                <td>Total contestants: </td> 
                                <td> {{ $organization->contestants()->count() }} </td>
                            </tr>
                            <tr>
                                <td>Total Rodeos: </td> 
                                <td> {{ $organization->rodeos()->count() }} </td>
                            </tr>
                        </table>                
                    </p>
                </div>
            </div><!--/card-->
        </div><!--/col-->
    </div><!--/row-->

</div>
@endsection
