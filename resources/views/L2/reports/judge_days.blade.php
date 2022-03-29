@extends('layouts.producer')

@section('content')
    @include('L2.reports._rodeo_report_header', ['active' => 'judge'])

    <div class="mt-4">
        {{ $rodeo->name }}
        <hr>
        <table>
            @for($i = 1; $i <= ($rodeo->starts_at->diffInDays($rodeo->ends_at) + 1); $i++)
                <tr>
                    <td>{{ $rodeo->starts_at->copy()->addDays($i)->toFormattedDateString() }}</td>
                    <td class="px-2"><a href="{{ route('L2.reports.judge', [$organization, $rodeo, $i]) }}" class="btn btn-primary btn-sm">Judge Sheet</a></td>
                </tr>
            @endfor
        </table>
    </div>
@endsection
