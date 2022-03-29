@extends('layouts.producer')

@section('content')
    @include('L2.reports._rodeo_report_header', ['active' => 'entries'])

    <div class="mt-4">
        {{ $rodeo->name }} <br>
        {{ $rodeo->starts_at->toFormattedDateString() }} &ndash; {{ $rodeo->ends_at->toFormattedDateString() }}
        <br><br>
        <a href="{{ route('L2.reports.entries', [$organization, $rodeo]) }}" class="btn btn-primary btn-sm">Entries Report</a>
    </div>
@endsection
