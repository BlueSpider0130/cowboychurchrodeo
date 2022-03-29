@extends('layouts.app')

@section('content')
<nav aria-label="breadcrumb" style="margin: -1.5rem 0 1.5rem 0;">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('toolbox', [$organization->id]) }}">Toolbox</a></li>
        <li class="breadcrumb-item"><a href="{{ route('L4.membership.home', $organization->id) }}">Membership</a></li>
    </ol>
</nav>

<div class="container">

    <x-session-alerts />

    <h1> {{ $series->name ? $series->name : "Series #{$series->id}" }} </h1>
    <hr>
    <p class="mb-5">
        Membership fee: ${{ number_format($series->membership_fee, 2) }} <br>
    </p>
    @foreach( $contestants as $contestant )

        <div class="card my-3 shadow-sm">
            <div class="card-header bg-white">
                <b>
                    {{ $contestant->name }}
                    <x-membership-badge :contestant="$contestant" :series="$series" style="margin-left: .5rem;" />
                </b>
            </div>

            <div class="card-body">

                @if( $membership = $contestant->memberships->where('series_id', $series->id)->first() )
                    <table>
                        <tr>
                            <td class="pr-2"> Membership registration: </td> 
                            <td style="white-space: nowrap;"> {{ $membership->created_at->toFormattedDateString() }} </td>
                        </tr>
                        <tr>
                            <td class="pr-2"> Paid: </td> 
                            <td style="white-space: nowrap;"> 
                                @if( $membership->paid )
                                    @if( $membership->payment  && $membership->payment->created_at )
                                        {{ $membership->payment->created_at->toFormattedDateString() }}
                                    @else
                                        Yes
                                    @endif
                                @else
                                    <span class="text-muted" style="font-size: .8rem;"> not paid </span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    @if( !$membership->paid ) 
                        {{--
                        <hr>
                        <a href="" class="btn btn-primary btn-sm"> Pay for membership </a>
                        --}}
                    @endif
                @else 
                    <a href="{{ route('L4.membership.create', [$organization->id, $series->id, $contestant->id]) }}" class="btn btn-primary btn-sm"> 
                        Register for membership 
                    </a>
                @endif

            </div>
        </div>
    @endforeach

</div>
@endsection