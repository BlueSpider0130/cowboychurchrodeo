@extends('layouts.producer')

@section('content')
    @include('L2.reports._rodeo_report_header', ['active' => $active])

    <p class="p-4">
        <i>There are no entries for this rodeo...</i>
    </p>
@endsection
