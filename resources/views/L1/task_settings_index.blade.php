@extends('layouts/admin')

@section('content')
<nav aria-label="breadcrumb" style=" margin: 0 -15px -1rem -15px;">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.task.index.open') }}"> Tasks </a>
        </li> </li>
        <li class="breadcrumb-item active" aria-current="page"> Settings </li>
    </ol>
</nav>

<div class="container-fluid py-4">

    <x-session-alerts />

    <h4> Task settings </h4>
    <hr>
    <br>

    <h5> Types </h5>
    <hr>
    <div class="mb-5">
        <table>
            @foreach( $types as $record )
                <tr> 
                    <td class="px-1"> 
                        {{ $record->name }} 
                    </td> 
                    <td class="pl-3"> 
                        <a href="{{ url()->route('admin.task.type.delete', [$record->id]) }}" class="text-danger" onclick="return confirm('Are you sure you want to delete this type?');"> 
                            <i class="fas fa-times"></i> 
                        </a> 
                    </td> 
                </tr> 
            @endforeach
        </table>

        <hr>

        <form method="post" action="{{ url()->route('admin.task.type.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-2">
                    <input type="text" class="form-control {{ $errors->has('type_name') ? 'is-invalid' : '' }}" name="type_name" value="{{ old('type_name') }}">
                    @if($errors->has('type_name'))
                        <div class="invalid-feedback">{{ $errors->first('type_name') }}</div>
                    @endif 
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary"> <i class="fas fa-plus"></i>&nbsp; Add type </button> 
                </div>
            </div> 
        </form>   
    </div>



    <h5> Priorities </h5>
    <hr>
    <div class="mb-5">
        <table>
            @foreach( $priorities as $record )
                <tr> 
                    <td class="px-1"> 
                        {{ $record->name }} 
                    </td> 
                    <td class="pl-3"> 
                        <a href="{{ url()->route('admin.task.priority.delete', [$record->id]) }}" class="text-danger" onclick="return confirm('Are you sure you want to delete this priority?');"> 
                            <i class="fas fa-times"></i> 
                        </a> 
                    </td> 
                </tr> 
            @endforeach
        </table>

        <hr>

        <form method="post" action="{{ url()->route('admin.task.priority.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-2">
                    <input priority="text" class="form-control {{ $errors->has('priority_name') ? 'is-invalid' : '' }}" name="priority_name" value="{{ old('priority_name') }}">
                    @if($errors->has('priority_name'))
                        <div class="invalid-feedback">{{ $errors->first('priority_name') }}</div>
                    @endif 
                </div>
                <div class="col-md-4">
                    <button priority="submit" class="btn btn-primary"> <i class="fas fa-plus"></i>&nbsp; Add priority </button> 
                </div>
            </div> 
        </form>   
    </div>



    <h5> Statuses </h5>
    <hr>
    <div class="mb-5">
        <table>
            @foreach( $statuses as $record )
                <tr> 
                    <td class="px-1"> 
                        {{ $record->name }} 
                    </td> 
                    <td class="pl-3"> 
                        <a href="{{ url()->route('admin.task.status.delete', [$record->id]) }}" class="text-danger" onclick="return confirm('Are you sure you want to delete this status?');"> 
                            <i class="fas fa-times"></i> 
                        </a> 
                    </td> 
                </tr> 
            @endforeach
        </table>

        <hr>

        <form method="post" action="{{ url()->route('admin.task.status.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-2">
                    <input status="text" class="form-control {{ $errors->has('status_name') ? 'is-invalid' : '' }}" name="status_name" value="{{ old('status_name') }}">
                    @if($errors->has('status_name'))
                        <div class="invalid-feedback">{{ $errors->first('status_name') }}</div>
                    @endif 
                </div>
                <div class="col-md-4">
                    <button status="submit" class="btn btn-primary"> <i class="fas fa-plus"></i>&nbsp; Add status </button> 
                </div>
            </div> 
        </form>   
    </div>


</div>
@endsection
