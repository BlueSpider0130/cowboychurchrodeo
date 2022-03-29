@extends('layouts.producer')

@section('content')
<div class="container">

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
