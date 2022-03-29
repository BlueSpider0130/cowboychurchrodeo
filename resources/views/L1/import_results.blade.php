@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    {{ $rodeo->name }} - Rodeo #{{ $rodeo->id }}
    <hr>
    {{ $day ? $day->toDateTimeString() : '' }}

    @if( !$results )
        no results...
    @else
        <table class="table">
            @foreach($results as $row)
                <tr>
                    <td>
                        @if(count($row['errors']) > 0)
                            <span class="text-danger">!</span> <br>
                            <span style="font-size: .7rem">Failed import</span>
                        @else
                            <span class="text-success">&check;</span><br>
                            <span style="">Imported</span>
                        @endif
                    </td>
                    <td>
                        <?php $info = (array) $row['data']; ?>
                        {{ implode(', ', $info) }} <br>
                        <span class="text-muted" style="font-size: .7rem">{!! json_encode($row['data']) !!}</span>
                        <hr>
                        @if($row['errors'])
                            @foreach($row['errors'] as $error)
                                <span class="text-danger">{{ $error }}</span><br>
                            @endforeach
                        @else
                            @foreach($row['log'] as $msg)
                                {{ $msg }} <br>
                            @endforeach
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    @endif

</div>
@endsection
