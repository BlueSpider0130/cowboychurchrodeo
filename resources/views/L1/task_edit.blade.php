@extends('layouts/admin')

@section('content')
<nav aria-label="breadcrumb" style=" margin: 0 -15px -1rem -15px;">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.task.index.open') }}"> Tasks </a>
        </li>
        <li class="breadcrumb-item"> <a href="{{ route('admin.task.show', $task->id) }}"> Task #{{ $task->id }} </a> </li>
        <li class="breadcrumb-item active" aria-current="page"> Edit </li>
    </ol>
</nav>

<div class="container-fluid py-4">

    <h4> Task #{{ $task->id }} </h4>
    <hr>
    <form method="post">
        @csrf 

        <div class="row">
            <div class="col-md-4">
                
                <label> Status </label>
                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status_id">
                    <option value=""> </option>
                    @foreach( $statuses as $id => $name )
                        <option value="{{ $id }}" {{ $id == old('status_id', $task->status_id) ? 'selected' : ''}}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
                @if($errors->has('status'))
                    <div class="invalid-feedback">{{ $errors->first('status') }}</div>
                @endif 
                <br> 

                <label> Type </label>
                <select class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type_id">
                    <option value=""> </option>
                    @foreach( $types as $id => $name )
                        <option value="{{ $id }}" {{ $id == old('type_id', $task->type_id) ? 'selected' : ''}}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
                @if($errors->has('type'))
                    <div class="invalid-feedback">{{ $errors->first('type') }}</div>
                @endif 
                <br> 

                <label> Priority </label>
                <select class="form-control {{ $errors->has('priority') ? 'is-invalid' : '' }}" name="priority_id">
                    <option value=""> </option>
                    @foreach( $priorities as $id => $name )
                        <option value="{{ $id }}" {{ $id == old('priority_id', $task->priority_id) ? 'selected' : ''}}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
                @if($errors->has('priority'))
                    <div class="invalid-feedback">{{ $errors->first('priority') }}</div>
                @endif 
                <br> 

            </div>
        </div>
        

        <label> Page </label>
        <input type="text" name="page" value="{{ old('page', $task->page) }}" class="form-control {{ $errors->has('page') ? 'is-invalid' : '' }}">
        @if($errors->has('page'))
            <div class="invalid-feedback">{{ $errors->first('page') }}</div>
        @endif 
        <br>

        <label> Description </label>
        <textarea rows="5" name="description" class="form-control {{ $errors->has('page') ? 'is-invalid' : '' }}">{{ old('description', $task->description) }}</textarea>
        @if($errors->has('description'))
            <div class="invalid-feedback">{{ $errors->first('description') }}</div>
        @endif 
        <br>

        <div>
            <input type="submit" class="btn btn-primary" value="Update">
            <a href="{{ url()->route('admin.task.show', $task->id) }}" class="btn btn-outline-secondary"> Cancel </a>           
        </div>

    </form>

</div>
@endsection
