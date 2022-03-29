@extends('layouts/admin')

@section('content')
<nav aria-label="breadcrumb" style=" margin: 0 -15px -1rem -15px;">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.task.index.open') }}"> Tasks </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page"> Task #{{ $task->id }} </li>
    </ol>
</nav>

<div class="container-fluid py-4">

    <x-session-alerts />

    <div class="row">
        <div class="col">
            <b>Task #{{ $task->id }}</b>
        </div>
        <div class="col d-md-none text-right">
            <a href="{{ route('admin.task.edit', $task->id) }}" class="btn btn-sm btn-outline-primary"> <i class="fas fa-edit"></i> Edit </a>
        </div>
    </div>

    <hr>

    <div class="d-none d-md-block text-right">
        <a href="{{ route('admin.task.edit', $task->id) }}" class="btn btn-sm btn-outline-primary"> <i class="fas fa-edit"></i> Edit </a>
    </div>

    <div class="row">
        <div class="col">
            <table>
                <tr>
                    <td class="pr-2"> <b> Status: </b> </td>
                    <td> {!! $task->status ? $task->status->name : '<i> pending </i>' !!} </td>
                </tr>
                <tr>
                    <td class="pr-2"> <b> Type: </b> </td>
                    <td> {{ $task->type ? $task->type->name : '' }} </td>
                </tr>
                <tr>
                    <td class="pr-2"> <b> Priority: </b> </td>
                    <td> {{ $task->priority ? $task->priority->name : '' }} </td>
                </tr>
            </table>
        </div>
    </div>

    <hr>


    <div>
        <table>
            @if( $task->created_by )
                <tr>
                    <td class="pr-2"> <b> Created by: </b> </td>
                    <td> {{ $task->created_by->name }} ({{ $task->created_by->email }}) </td>
                </tr>
            @endif

            <tr>
                <td class="pr-2"> <b> Created: </b> </td>
                <td> {{ $task->created_at ? $task->created_at->toFormattedDateString() : '' }} </td>
            </tr>
        </table>
    </div>

    <hr>

    <div class="py-3">
        <table>
            <tr> 
                <td class="pr-2"> <b> Page: </b> </td>
                <td> {{ $task->page }} </td>
            </tr>
        </table>
    </div>

    <div class="row">
        <div class="col col-md-6" style="white-space: pre-wrap;">{{ $task->description }}</div>
    </div>
   
    <hr class="mt-5">

    <h5>Comments ({{ $task->comments->count() }})</h5>
    <hr>

    @foreach( $task->comments as $comment )
        <div class="row my-2">
            <div class="col col-md-6">
                <b>{{ $comment->user->name }}</b>
                <div class="white-space: pre-wrap">{{ $comment->body }}</div>
            </div>
        </div>
    @endforeach

    <hr>

    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#comment-modal">
        <i class="fas fa-plus"></i> Add comment
    </button>

    <div class="modal fade" id="comment-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                
                <form method="post" action="{{ route('admin.task.comment.store') }}">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> Comment </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <textarea name="body" class="form-control" required></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add comment</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    
</div>
@endsection
