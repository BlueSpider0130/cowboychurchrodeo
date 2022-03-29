@extends('layouts.producer')

@section('content')
<div class="container-fluid">
    <x-session-alerts />

    <h1> Groups </h1>

     @if( $groups->count() < 1 )
        <hr>
        <p>
            <i>No groups created yet...</i>
        </p>
    @else
        <table class="table bg-white border rounded">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                @foreach( $groups as $group )
                    <tr>
                        <td style="white-space: nowrap;">{{ $group->name }}</td>
                        <td>{{ $group->description }}</td>
                        <td class="text-center"> 
                            <a href="{{ route('L2.groups.edit', [$organization->id, $group->id]) }}" class="text-secondary" title="Edit">
                                <i class="fas fa-edit fa-lg mx-2"></i>
                            </a>

                            <x-delete-button url="{{ route('L2.groups.destroy', [$organization, $group]) }}" message="Are you sure you want to delete this group?">
                                <i class="fas fa-trash fa-lg text-danger"></i>
                            </x-delete-button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ route('L2.groups.create', $organization) }}" class="btn btn-primary"> 
        <i class="fas fa-plus pr-1"></i> Add new group 
    </a>

</div>
@endsection
