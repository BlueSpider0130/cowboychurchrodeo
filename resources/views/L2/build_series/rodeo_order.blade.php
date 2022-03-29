@extends('layouts.producer')

@section('content')
<div class="container-fluid">

    <a href="{{ route('L2.build.series.show', [$organization, $series]) }}"> Series {{ $series->name }} </a>
    <hr>
    <br>

    <x-session-alerts />

    <h1> {{ $rodeo->name ? $rodeo->name : "Rodeo #{$rodeo->id}" }} </h1>
    <hr>

    <form method="post" action="">
        @csrf
        <div class="row">
            <div class="col-lg-6">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <table class="table bg-white">
                    <thead class="font-weight-bold">
                        <tr>
                            <td>Group</td>
                            <td>Event</td>
                            <td>Order</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach( $competitions as $competition )
                            <tr>
                                <td>{{ $competition->group->name }}</td>
                                <td>{{ $competition->event->name }}</td>
                                <td style="width: 10rem">
                                    <input type="text" class="form-control" name="order[{{ $competition->id }}]" value="{{ old("order.{$competition->id}", $competition->order) }}" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right">
                                <input type="submit" class="btn btn-primary" value="Save">
                            </td>
                        </tr>
                    </tfoot>
                </table>       
            </div>
        </div>
    </form>
</div>
@endsection
