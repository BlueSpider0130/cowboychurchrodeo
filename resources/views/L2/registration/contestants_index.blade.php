@extends('layouts.producer')

@section('content')
<div class="mt-n4 mx-n4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"> 
                <a href="{{ route('L2.registration.rodeos.index', [$organization->id]) }}"> Rodeos </a> 
            </li>
            <li class="breadcrumb-item active" aria-current="page"> Contestants </li>
        </ol>
    </nav>
</div>

<div class="container-fluid">

    <h1> Rodeo registration </h1>
    <hr>
    @include('partials.registration.rodeo_info_card')

    <div class="row justify-content-center mt-3 mt-md-5 mb-3 mb-md-5">
        <div class="col-12 col-md-10 col-lg-8" >
            <x-list-builder.search />
        </div>
    </div>


    <h2> Select contestants </h2>

    <table class="table table-responsive-cards bg-white border rounded">
        <thead>
            <tr> 
                <th> Last name </th>
                <th> First name </th>
                <th class="text-md-center"> Contestant details </th>
                <th class="text-md-center"> Registered </th>
                <th> </th>
            </tr>
        </thead>
        <tbody>
            @foreach( $contestants as $contestant )
                <tr>
                    <td class="d-inline-block d-md-table-cell"> 
                        {{ $contestant->last_name }}<span class="d-md-none">,</span>
                    </td>
                    <td class="d-inline-block d-md-table-cell"> 
                        {{ $contestant->first_name }} 
                    </td>
                    <td class="border-top pt-3 pt-md-2 text-md-center"> 
                        <a href="{{ route('L2.contestants.show', [$organization->id, $contestant->id]) }}"> 
                            <i class="far fa-address-card fa-2x"></i> 
                            <span class="d-inline-block ml-2 d-md-none"> contestant details
                        </a>
                    </td>
                    <td class="text-md-center">
                        @if( $entry = $contestant->rodeo_entries->where('rodeo_id', $rodeo->id)->first() )
                            <span class="text-success"> <i class="fas fa-check"></i> </span>
                            <span class="d-inline-block ml-2 d-md-none"> registered
                        @endif                            
                    </td>
                    <td class="text-md-center"> 
                        <a href="{{ route('L2.registration.show', [$organization->id, $rodeo->id, $contestant->id]) }}" class="btn btn-outline-primary"> Registration </a> 
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection