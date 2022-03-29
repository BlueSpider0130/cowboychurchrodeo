@extends('layouts.producer')

@push('head')
<style>
    @media print {
        #reports-header {
            display: none;
        }
    }
</style>
@endpush

@section('content')
    <div id="reports-header">
        <h1> Reports </h1>
        <hr>
        <p class="mt-1 mb-4"> 
            <small class="text-muted"> Reports may take a while to generate depending on the number of entries, contestants, and users. </small> 
        </p>

        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link {{ isset($activeHeaderTab) && $activeHeaderTab == 'rodeo_reports' ? 'active' : '' }}" href="{{ route('L2.reports.home', $organization->id) }}">
                    Rodeo Reports
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ isset($activeHeaderTab) && $activeHeaderTab == 'emails' ? 'active' : '' }}" href="{{ route('L2.reports.emails', $organization->id) }}">
                    Emails
                </a>
            </li>
        </ul>
    </div>

    <div class="">
        @yield('reports_content')
    </div>
</div>
@endsection
