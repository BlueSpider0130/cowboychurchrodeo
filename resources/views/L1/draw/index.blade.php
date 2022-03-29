@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <x-session-alerts />

    <div class="mb-4">
        <h1> Draw </h1>
        <hr class="mt-1 mb-3 mb-md-2">
    </div>

    <table class="table bg-white border table-responsive-cards">
        @foreach($rodeos as $rodeo)
            <tr>
                <td>{{ $rodeo->name }}</td>
                <td>{{ $rodeo->starts_at }} &ndash; {{ $rodeo->ends_at }}</td>
                <td>
                    <a href="{{ route('admin.draw.clear', $rodeo) }}" class="btn btn-primary btn-sm" onclick="confirm('Are you sure you want to clear the draw? This cannot be undone.')">Clear draw</a>
                </td>
            </tr>
        @endforeach
    </table>
@endsection