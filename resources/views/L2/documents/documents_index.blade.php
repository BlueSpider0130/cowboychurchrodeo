@extends('layouts.producer')

@section('content')
<div class="container-fluid">

    <x-session-alerts />

    <h1> Uploaded documents </h1>

     @if( $documents->count() < 1 )
        <hr>
        <p>
            <i>No documents uploaded yet...</i>
        </p>
    @else
        <table class="table bg-white border rounded">
            <thead>
                <tr><th>Name</th><th>Filename</th><th>Uploaded</th><th> </th></tr>
            </thead>
            <tbody>
                @foreach( $documents as $document )
                    <tr>
                        <td>
                            <span data-toggle="tooltip" data-placement="bottom" title="{{ $document->description }}">
                                {{ $document->name }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('documents.download', $document->id) }}">{{ $document->filename }}</a>
                        </td>
                        <td>{{ $document->created_at->toFormattedDateString() }}</td>
                        <td class="text-center"> 

                            <a href="{{ route('L2.documents.edit', [$organization->id, $document->id]) }}" class="text-secondary" title="Edit">
                                <i class="fas fa-edit fa-lg mx-2"></i>
                            </a>


                            <x-delete-button 
                                url="{{ route('L2.documents.destroy', [$organization->id, $document->id]) }}" 
                                message="Are you sure you want to delete this document?"
                            >
                                <i class="fas fa-trash fa-lg px-1 text-danger"></i>
                            </x-delete-button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ route('L2.documents.create', $organization->id) }}" class="btn btn-primary"> 
        <i class="fas fa-plus pr-1"></i> Add new document 
    </a>

</div>
@endsection
