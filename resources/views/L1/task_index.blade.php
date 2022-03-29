@extends('layouts/admin')

@section('content')
<div class="py-3">

    <x-session-alerts />

    <h3> Tasks </h3>
    
    <div class="row border-top border-grey pt-3">

        <div class="col">
            <a href="{{ url()->route('admin.task.create') }}" class="btn btn-sm btn-primary"> <i class="fas fa-plus"></i> &nbsp; Add task </a>
        </div>

        <div class="col text-right">
            <a href="{{  url()->route('admin.task.settings.index') }}" class="text-secondary" title="settings"> 
                <i class="fas fa-cog fa-lg"></i> Settings
            </a>
        </div>
    </div>

    <hr>
   
    <div class="d-flex mt-4">
        <div class="flex-grow-1 mb-1">
            <span class="d-none d-md-inline"> Filter: </span>
            <a 
                href="{{ route('admin.task.index.open') }}" 
                class="btn btn-sm {{ 'open' == $filter ? 'btn-secondary' : 'btn-outline-secondary' }}"
            > 
                Open 
            </a> 
            <a 
                href="{{ route('admin.task.index.closed') }}" 
                class="btn btn-sm {{ 'closed' == $filter ? 'btn-secondary' : 'btn-outline-secondary' }}"
            > 
                Closed 
            </a>
            <a 
                href="{{ route('admin.task.index') }}" 
                class="btn btn-sm {{ 'all' == $filter ? 'btn-secondary' : 'btn-outline-secondary' }}"
            > 
                All 
            </a>
        </div>

        <div class="flex-shink-1 text-right mb-1">
            <span class="d-none d-md-inline">  Sort:&nbsp;&nbsp; </span>
            <select id="sort" style="
                background-clip: padding-box;
                background-color: #fff;
                border: 1px solid #a1cbef; 
                border-radius: .25rem; 
                box-shadow: rgba(52,144,220,.25) 0 0 0 .2rem; 
                box-sizing: border-box; 
                color: #495057;
                font-size: .9rem;
                font-weight: 400;
                margin: 0;
                outline: 0;
                overflow-wrap: normal;
                text-transform: none;
                transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
                "
                onchange="window.location.href = window.location.href.split('?')[0] + (this.value ? ('?sort=' + this.value) : '')"
            >
                <option></option>
                <option value="date_desc" {{ 'date_desc' == $sort ? 'selected' : '' }}> Date  &darr; </option>
                <option value="date_asc" {{ 'date_asc' == $sort ? 'selected' : '' }}> Date  &uarr; </option>
                <option value="status" {{ 'status' == $sort ? 'selected' : '' }}> Status </option>
                <option value="type" {{ 'type' == $sort ? 'selected' : '' }}> Type </option>
                <option value="priority" {{ 'priority' == $sort ? 'selected' : '' }}> Priority </option>
            </select>
        </div>    
    </div>

    <div id="task-list">
        @foreach( $tasks->all() as $index => $task )

            <div class="d-flex" style="{{ $index%2 ? '' : 'background-color: #EBEDEF' }}">

                <div class="p-2">
                    @if( $task->closed )
                        <a 
                            href="{{ route('admin.task.open', $task->id) }}?r={{ Request::route()->getName() }}"
                            onclick="return confirm('Are you sure you want to re-open this task?');"
                        >
                            <i class="far fa-check-circle fa-lg text-success"></i>
                        </a>    
                    @else
                        <a 
                            href="{{ route('admin.task.close', $task->id) }}?r={{ Request::route()->getName() }}"
                            onclick="return confirm('Are you sure you want to close this task?');"
                        >
                            <i class="far fa-circle fa-lg text-secondary"></i>
                        </a>
                    @endif
                </div>

                <div class="p-2 flex-grow-1">

                    <div class="row">

                        <div class="col-auto col-md-auto order-1 font-weight-bold">
                            #{{ $task->id }}
                        </div>

                        <div class="col col-md-auto order-2 order-md-10 text-right px-3 px-md-4">

                            <div class="btn-group dropleft">
                                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" style="border-radius: .8rem"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.task.show', $task->id) }}">
                                        View task
                                    </a>
                                    @if( $task->closed )
                                        <a 
                                            class="dropdown-item" 
                                            href="{{ route('admin.task.open', $task->id) }}?r={{ Request::route()->getName() }}"
                                        >
                                            Re-open task
                                        </a>    
                                    @else
                                        <a 
                                            class="dropdown-item" 
                                            href="{{ route('admin.task.close', $task->id) }}?r={{ Request::route()->getName() }}"
                                        >
                                            Close task
                                        </a>
                                    @endif
                                </div>
                            </div>

                        </div>

                        <div class="col-12 col-md-auto order-3">
                            {{ $task->created_at ? $task->created_at->toFormattedDateString() : '' }}
                        </div>

                        <div class="col-12 col-md order-4">
                            @if( $task->created_by )
                                {{ $task->created_by->name }}
                            @endif
                        </div>

                        <div class="col-12 col-md order-5 order-md-6">
                            <b>Type</b><br>
                            {{ $task->type ? $task->type->name : '' }}
                        </div>

                        <div class="col-12 col-md order-6 order-md-7">
                            <b>Priority</b> <br>
                            {{ $task->priority ? $task->priority->name : '' }}
                        </div>

                        <div class="col-12 col-md order-7 order-md-8">
                            <b>Status</b> <br>
                            {!! $task->status ? $task->status->name : '<i>pending</i>' !!}
                        </div>

                        <div class="col-12 col-md order-8 order-md-4" style="overflow-wrap: break-word; word-wrap: break-word; word-break: break-word;">
                            <b> Page: </b> <br>
                            {{ $task->page }}
                        </div>

                        <div class="col-12 col-md-3 order-9 order-md-5" style="overflow-wrap: break-word; word-wrap: break-word; word-break: break-word;">
                            <b> Description: </b> <br>
                            @if( strlen($task->description) > 130 )
                                {{ substr($task->description, 0, 130) }}... <br>
                                [<a href="{{ route('admin.task.show', $task->id) }}">MORE</a>]
                            @else
                                {{ $task->description }} 
                            @endif

                            @if( $task->comments->count() > 0 )
                                <hr>
                                <a href="{{ route('admin.task.show', $task->id) }}" class="text-dark"> 
                                    <span class="badge badge-pill badge-info text-white">{{ $task->comments->count() }}</span>                             
                                    comment{{ $task->comments->count() > 1 ? 's' : '' }}
                                </a>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

        @endforeach
    </div>

    {{ $tasks->links() }}

</div>
@endsection
