@extends('layouts/app')

@push('top-navbar-right-side-start')
    <form class="form-inline my-2 my-lg-0" method="GET" action="{{ route('organizations.index') }}">
        <input 
            id="navbar-search-input"
            name="search" 
            class="form-control mr-sm-2" 
            type="text" 
            placeholder="Search for organization..." 
            aria-label="Search for organization"
        >
        <button class="btn btn-outline-info my-2 my-sm-0" type="submit">Search</button>
    </form>
@endpush