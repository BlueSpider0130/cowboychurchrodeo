@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <x-session-alerts />

    <div class="mb-4">
        <h1> Contestants </h1>
        <hr class="mt-1 mb-3 mb-md-2">
    </div>

    <div class="row justify-content-center mt-3 mt-md-5 mb-3 mb-md-5">
        <div class="col-12 col-md-10 col-lg-8" >
            <x-list-builder.search />
        </div>
    </div>


    <div class="row mb-2">
        <div class="col-12 col-sm">
            <i> Showing {{ $contestants->count() }} of {{ $contestants->total() }} total contestants </i>
        </div>
        <div class="col-12 col-sm text-right">
            @if( $contestants->total() > $contestants->perPage() )
                Results per page: <x-list-builder.per-page-links :paginator="$contestants" :options="[ 25, 50, 100, 'All']" />
            @endif
        </div>
    </div>

    <table class="table bg-white border table-responsive-cards">
        <thead>
            <tr>
                <th class="text-nowrap">
                    @if( in_array('last_name', $sortable) )
                        <x-list-builder.sort-by-table-header sort-by="last_name"> Last name </x-list-builder.sort-by-table-header>
                    @else
                        Last name
                    @endif
                </th>
                <th class="text-nowrap">
                    @if( in_array('first_name', $sortable) )
                        <x-list-builder.sort-by-table-header sort-by="first_name"> First name </x-list-builder.sort-by-table-header>
                    @else
                        First name
                    @endif
                </th>
                <th class="text-nowrap"> Birthdate </th>
                <th class="text-nowrap"> User account(s) </th>
                <th> &nbsp; </th>
            </tr>
        </thead>
        <tbody>
            @php 
                $contestants->load('users');
            @endphp
            @foreach( $contestants as $contestant )
                <tr>
                    <td class="text-nowrap d-inline-block d-md-table-cell font-weight-xs-bold">
                        {{ $contestant->last_name }}<span class="d-md-none">,</span>
                    </td>

                    <td class="text-nowrap d-inline-block d-md-table-cell font-weight-xs-bold">
                        {{ $contestant->first_name }} 
                    </td>

                    <td class="text-nowrap {{ !$contestant->birthdate ? 'trc-d-none' : '' }}">
                        {{ $contestant->birthdate ? $contestant->birthdate->toFormattedDateString() : '' }}
                    </td>

                    <td class="{{ $contestant->users->count() < 1 ? 'trc-d-none' : '' }}">
                        <ul style="list-style-type: none; margin: 0; padding: 0;">
                            @foreach( $contestant->users as $user )
                                <li>{{ $user->name }} - {{ $user->email }}</li>
                            @endforeach
                        </ul>
                    </td>

                    <td class="text-md-center">
                        <a href="{{ route('admin.contestants.show', $contestant) }}" class="btn btn-outline-primary btn-sm">
                            Details 
                        </a>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $contestants->links() }}
</div>
@endsection
