@extends('layouts.producer')

@section('content')
<div class="container-fluid py-4">

    <x-session-alerts />

    <div class="mb-5">
        <a href="{{ route('L2.competitions.index', $organization) }}"> 
            <i class="fas fa-chevron-left"></i> 
            Competitions
        </a>
    </div>

    <div class="row mb-5">
        <div class="col-12 col-md-12 col-lg-8">        

            <div class="card">
                <div class="card-header bg-white">
                    <h1 class="my-0"> #{{ $competition->id }} {{ $competition->name ? $competition->name : '' }} </h1>
                </div>
                <div class="card-body">

                    @if( $competition->name || $competition->series || $competition->rodeo )
                        <table class="mb-4">
                            @if( $competition->name )
                                <tr>
                                    <td class="font-weight-bold pr-3"> Name </td>
                                    <td> {{ $competition->name }} </td>
                                </tr>
                            @endif
                            @if( $competition->series )
                                <tr>
                                    <td class="font-weight-bold pr-3"> Series </td>
                                    <td> {{ $competition->series->name ? $competition->series->name : $competition->series->id }} </td>
                                </tr>
                            @endif
                            @if( $competition->rodeo )
                                <tr>
                                    <td class="font-weight-bold pr-3"> Rodeo </td>
                                    <td> {{ $competition->rodeo->name ? $competition->rodeo->name : $competition->rodeo->id }} </td>
                                </tr>
                            @endif
                        </table>
                    @endif

                    <div class="mb-4"> 
                        @if( $competition->instances->count() < 1 )
                            <hr>
                            <i> No days / times created yet... </i>
                        @else

                            <table class="table bg-white border"> 
                                <thead>
                                    <tr>
                                        <th> Date </th>
                                        <th> Start </th>
                                        <th> End </th>
                                        <th> Location </th>
                                        <th> </th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    @foreach( $competition->instances as $instance )
                                        <tr>
                                            <td style="white-space: nowrap;"> {{ $instance->date ? $instance->date->format('D, M d, Y') : 'TBA' }} </td>
                                            <td style="white-space: nowrap;"> 
                                                @if( $instance->date  &&  !$instance->anytime )
                                                    {{ $instance->start_time ? $instance->start_time : 'TBA' }}
                                                @else

                                                @endif
                                            </td>
                                            <td style="white-space: nowrap;"> 
                                                @if( $instance->date  &&  !$instance->anytime )
                                                    {{ $instance->end_time ? $instance->end_time : '' }}
                                                @else 

                                                @endif
                                            </td>
                                            <td> {{ $instance->location }} </td>
                                            <td>
                                                <button class="btn-reset" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-cog"></i>
                                                </button>

                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="{ { route('post.edit', [$post->id]) }}">
                                                        <i class="fas fa-edit pr-2"></i> Edit
                                                    </a>
                                                    <button 
                                                        type="button"
                                                        class="dropdown-item text-danger" 
                                                         onclick="confirmDelete('delete-instance-{{ $instance->id }}', 'Are you sure you want to delete this instance?');"
                                                    > 
                                                        <i class="fas fa-trash pr-2"></i> Delete 
                                                    </button>
                                                    <form id="delete-instance-{{ $instance->id }}" method="post" action="{ { route('') }}">
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        @csrf()
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif 
                    </div>

                </div>
            </div><!--/card -->
        </div>
    </div><!--/row-->

</div>
@endsection
