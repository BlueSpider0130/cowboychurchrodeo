@extends('layouts.producer')

@section('content')
<div class="container-fluid" style="position: relative;">

    <x-session-alerts />

    @error('contestants')
        <div class="alert alert-danger"> {{ $message }} </div>
    @enderror
    @error('contestants.*')
        <div class="alert alert-danger"> {{ $message }} </div>
    @enderror

    <h1> {{ $rodeo->name ? $rodeo->name : 'Rodeo #'.$rodeo->id }} </h1>
    
    <ul class="nav nav-tabs mt-4 mb-4">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('L3.check-in.contestants', [$organization->id, $rodeo->id]) }}">Check in</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="#">Checked in</a>
        </li>
    </ul>
   
    <div class="mt-5">
        <h2> Checked in </h2>
        @if( $checkedInEntries->count() < 1)
            <hr> 
            <i>No contestants checked in yet...</i>
        @else

            <table class="table bg-light border">
                <thead>
                    <tr> 
                        <th> Contestant </th>
                        <th> </th><!--   gender -->
                        <th> </th><!--   membership badge header -->
                        <th> Check in notes </th>
                        <th> Checked in notes </th>
                        <th> </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach( $checkedInEntries as $entry )
                        <tr>
                            <td> 
                                {{ $entry->contestant->lexical_name_order }}                                 
                            </td>
                            <td>
                                @if( $entry->contestant->sex )
                                    <img src="/assets/{{$entry->contestant->sex}}.png">
                                @endif
                            </td>
                            <td>
                                @if( $rodeo->series_id )
                                    <x-membership-badge :contestant="$entry->contestant" :series="$rodeo->series_id" class="pl-3" />
                                @endif 
                            </td>                                
                            <td> {{ $entry->check_in_notes }} </td>
                            <td> {{ $entry->checked_in_notes }} </td>
                            <td class="text-md-center"> 
                                <form method="post" action="{{ route('L3.check-in.destroy', [$organization->id, $entry->id]) }}">
                                    @method('delete')
                                    @csrf()
                                    <button type="submit" class="btn btn-outline-danger btn-sm"> 
                                        <i class="fas fa-undo-alt"></i>
                                        Undo check-in
                                    </button>
                                </form>
                             </td>
                        </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        @endif

</div>
@endsection