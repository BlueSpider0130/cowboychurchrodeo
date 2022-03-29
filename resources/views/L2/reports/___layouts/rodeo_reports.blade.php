@php
    $activeHeaderTab = "rodeo_reports";
@endphp

@extends('L2.reports.layouts.reports')

@push('head')
<style>
    @media print {
        #rodeo-reports-header {
            display: none;
        }
    }
</style>
@endpush

@section('reports_content')
    <div class="px-3 py-4 border">
        <div id="rodeo-reports-header">
            <div class="mb-5">
                <h2 class="h-reset" style="font-size: 1.15rem; font-weight: bold">{{ $rodeo->name }}</h2>
                <hr class="my-1">
                {{ $rodeo->formattedStartDate() }} &ndash; {{ $rodeo->formattedEndDate() }}
            </div>
                
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link {{ isset($activeRodeoHeaderTab) && $activeRodeoHeaderTab == 'entries' ? 'active' : '' }}" href="{{ route('L2.reports.entries', [$organization, $rodeo]) }}">
                        Rodeo Entries
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link {{ isset($activeRodeoHeaderTab) && $activeRodeoHeaderTab == 'draw' ? 'active' : '' }} dropdown" 
                        href="#"
                        id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                    >
                        Draw Sheet
                    </a>
                    <div class="dropdown-menu bg-light" aria-labelledby="dropdownMenuLink">
                        <a class="dropdown-item" href="{{ route('L2.reports.draw', [$organization, $rodeo]) }}">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </li>
                                <li class="nav-item dropdown">
                    <a class="nav-link {{ isset($activeRodeoHeaderTab) && $activeRodeoHeaderTab == 'draw' ? 'active' : '' }} dropdown" 
                        href="#"
                        id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                    >
                        Judge Sheet
                    </a>
                    <div class="dropdown-menu bg-light" aria-labelledby="dropdownMenuLink">
                        @for($i = 0; $i < $rodeo->ends_at->diffInDays($rodeo->starts_at); $i++)
                            <a class="dropdown-item dropright" data-toggle="dropdown">{{ $rodeo->starts_at->copy()->addDays($i)->toFormattedDateString() }}</a>
                            <div class="dropdown-menu bg-light" aria-labelledby="dropdownMenuLink">
                                @foreach( $rodeo->competitions()->with(['event', 'group'])->get() as $c)
                                    <a class="dropdown-item" href="">{{ $c->name }}</a>
                                @endforeach
                            </div>
                        @endfor
                    </div>

                </li>
                <li class="nav-item">
                    <a class="nav-link {{ isset($activeRodeoHeaderTab) && $activeRodeoHeaderTab == 'judge' ? 'active' : '' }}" href="{{ route('L2.reports.judge', [$organization, $rodeo]) }}">
                        Other
                    </a>
                </li>
            </ul>
        </div>

        <div class="">
            @yield('rodeo_reports_content')
        </div>
    </div>
</div>
@endsection