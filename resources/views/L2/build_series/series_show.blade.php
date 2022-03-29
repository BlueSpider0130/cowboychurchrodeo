@extends('layouts.producer')

@section('content')
<div class="container-fluid">

    <x-session-alerts />

    <h1> {{ $series->name }} </h1>
    <hr>
    <div class="text-right">
        <div class="dropdown">
            <a hre="#" role="button" class="text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-ellipsis-h fa-lg"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="{{ route('L2.build.series.index', $organization) }}">
                    Return to build series 
                </a>
                <button class="dropdown-item" type="button" onclick="if( confirm('Are you sure you want to delete this series?') ) { document.getElementById('series-delete-form').submit(); }">
                    <span class="text-danger">Delete this series</span>
                </button>
                <form method="post" action="{{ route('L2.build.series.destroy', [$organization, $series]) }}" class="d-none" id="series-delete-form"> @method('delete') @csrf </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-10 col-lg-8">      

                <!-- series info -->
                <div class="card mb-5">
                    <div class="card-body">

                        <div class="row"> 
                            <div class="col">
                                {{ $series->name }} 
                            </div>
                            <div class="col-2 text-right">
                                <a href="{{ route('L2.build.series.edit', [$organization, $series]) }}" class="text-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>

                        <hr class="my-1">
                        <div>
                            {{ $series->starts_at ? $series->starts_at->toFormattedDateString() : 'TBA'}} 
                            &ndash; 
                            {{ $series->ends_at ? $series->ends_at->toFormattedDateString() : 'TBA' }}
                        </div>

                        @if( $series->description )
                            <p class="mt-2">{{ $series->description }}</p>
                        @endif

                        <table class="mt-2"> 
                            <tr>
                                <td class="pr-2"> Membership fee: </td>
                                <td> ${{ $series->membership_fee ? number_format( $series->membership_fee, 2) : '0.00' }} </td>
                            </tr>
                        </table>
                          
                    </div>
                </div><!--/card-->
                

                <!-- documents -->
                <h2 style="font-weight: bold; font-size: 1rem;"> Documents </h2>
                <div class="mb-5">
                    @if( $series->documents->count() > 0 )
                        <table class="table bg-white border rounded table-responsive-cards">
                            <tbody> 
                                @foreach( $series->documents as $document )
                                    <tr>
                                        <td> {{ $document->name }} </td>
                                        <td> {{ $document->filename }} </td>
                                        <td>
                                            <a 
                                                href="#" 
                                                title="Remove from series" 
                                                class="text-danger" 
                                                onclick="
                                                    if( confirm('Are you sure you want to remove this document from the series?') )  
                                                    {
                                                        document.getElementById('remove-document-{{ $document->id }}-form').submit();
                                                    }
                                                    else { return false; }
                                                "
                                            >
                                                <span class="d-none d-md-block">
                                                    <i class="far fa-times-circle"></i>
                                                </span>
                                                <button class="btn btn-outline-danger btn-sm d-md-none">Remove</button>
                                            </a>
                                            <form method="post" action="{{ route('L2.build.series.documents.remove', [$organization, $series, $document]) }}" id="remove-document-{{ $document->id }}-form">
                                                @csrf
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <hr>
                    @endif
                    <a href="{{ route('L2.build.series.documents.add', [$organization, $series]) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus pr-1"></i> 
                        Add documents
                    </a>
                </div>


                <h2 style="font-weight: bold; font-size: 1rem;"> Rodeos </h2>
                <div class="mb-5" >
                    @if( $series->rodeos()->count() > 0 )
                        <table class="table bg-white border rounded table-responsive-cards">
                            <thead>
                                <tr>
                                    <th> Name </th>
                                    <th> Start </th>
                                    <th> End </th>
                                    <th class="text-md-center"> Number of events </th>
                                    <th> &nbsp; </th>
                                    <th> &nbsp; </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach( $series->rodeos()->with(['competitions'])->orderBy('starts_at')->get() as $rodeo )
                                    <tr>
                                        <td> {{ $rodeo->name }} </td>
                                        <td> 
                                            <x-rodeo-date :date="$rodeo->starts_at" default="TBA" />
                                        </td>
                                        <td>
                                            <x-rodeo-date :date="$rodeo->ends_at" default="TBA" />
                                        </td>
                                        <td class="d-none d-md-table-cell text-md-center">
                                            {{ $rodeo->competitions->count() }}
                                        </td>
                                        <td> 
                                            @if( $rodeo->competitions->count() > 0 )
                                                <a 
                                                    href="{{ route('L2.build.series.rodeos.order', [$organization, $series, $rodeo]) }}" 
                                                    class="btn btn-outline-secondary btn-sm"
                                                > Event order </a>
                                            @endif
                                        </td>
                                        <td> 
                                            <a 
                                                href="{{ route('L2.build.series.rodeos.show', [$organization, $series, $rodeo]) }}" 
                                                class="btn btn-outline-primary btn-sm"
                                            > Details </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else 
                        <hr>
                    @endif
                    <a href="{{ route('L2.build.series.rodeos.create', [$organization, $series]) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus pr-1"></i> 
                        Add rodeo
                    </a>
                </div>

        </div><!--col-->
    </div><!--row-->
</div>
@endsection
