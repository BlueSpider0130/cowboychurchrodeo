@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    
    <h1> Dashboard </h1>
    <hr>

    <div class="row">
        <div class="col col-md-5">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"> Summary </h5>
                    <p class="card-text">
                        <table class="table">
                            <tr>
                                <td>Total organizations: </td> 
                                <td> {{ $organizationCount }} </td>
                            </tr>
                            <tr>
                                <td>Total users: </td> 
                                <td> {{ $userCount }} </td>
                            </tr>
                        </table>                
                    </p>
                </div>
            </div><!--/card-->
        </div><!--/col-->
    </div><!--/row-->

</div>
@endsection

