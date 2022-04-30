@extends('layouts.producer')

@section('content')
<div class="container-fluid" style="position: relative;">
 
    <div>
        <h1> {{ $rodeo->name ? $rodeo->name : 'Rodeo #'.$rodeo->id }} </h1>
        <hr class="mb-1">
        <x-rodeo-date :date="$rodeo->starts_at" /> &ndash; <x-rodeo-date :date="$rodeo->ends_at" />
    </div>

    <div class="my-4">
        <div class="card d-inline-block">
            <div class="card-body px-4 pt-2 pb-4">

                <table class="check-in-summary-table">
                    @php
                        $total = 0;
                        $excludedGroupIds = $rodeo->group_office_fee_exceptions->pluck('id')->toArray();
                        $excludedEventIds = $rodeo->event_office_fee_exceptions->pluck('id')->toArray();
                        $membershipFee =  $rodeo->series->membership_fee ? $rodeo->series->membership_fee : 0;
                    @endphp

                    @foreach( $contestants as $contestant )
                        @php
                            $subtotal = 0; 
                            $officeFeeApplicable = false;
                        @endphp

                        <tr class="name-row">
                            <td colspan="4"> 
                                {{ $contestant->lexical_name_order }} 
                                @if( $rodeo->series_id )
                                    <x-membership-badge :contestant="$contestant" :series="$rodeo->series_id" class="pl-3" />
                                @endif 
                            </td>
                        </tr>
                        
                        @foreach( $entries->where('contestant_id', $contestant->id) as $entry )
                            <tr> 
                                <td> &nbsp; </td>
                                <td> 
                                    @if( $entry->competition->group_id )
                                        {{ $entry->competition->group->name }} - 
                                    @endif
                                    {{ $entry->competition->event->name }}
                                    @if( $entry->instance && $entry->instance->starts_at )
                                        <span class="text-muted" style="display: inline-block; margin-left: .5rem; font-size: .75rem">
                                            ({{ $entry->instance->starts_at->format('l') }})
                                        </span>
                                    @endif
                                </td>

                                <td> 
                                    @if( $entry->no_fee )
                                        <span class="text-muted"> N/A </span>                            
                                    @else                                                
                                            $ 
                                            <span style="@if($entry->paid) text-decoration: line-through @endif">
                                                {{ $entry->competition->entry_fee ? $entry->competition->entry_fee : '0.00' }} 
                                            </span>
                                        @php
                                            if( !$entry->paid )
                                            {
                                                $subtotal += $entry->competition->entry_fee;
                                            }

                                            if( !in_array($entry->competition->group->id, $excludedGroupIds) )
                                            {
                                                $officeFeeApplicable = true;
                                            }

                                            if( !in_array($entry->competition->event->id, $excludedEventIds) )
                                            {
                                                $officeFeeApplicable = true;
                                            }
                                        @endphp
                                    @endif
                                </td>

                                <td> 
                                    @if( $entry->no_fee ) 
                                        <span class="text-muted" style="font-size: .8rem; font-style: italic;">* No fee for contestant</span>
                                    @elseif( $entry->paid )
                                        <span class="pill-badge-paid"> Paid </span>
                                    @endif
                                </td>

                            </tr>
                        @endforeach

                        @php
                            $subtotal += ( $officeFeeApplicable  &&  $rodeo->office_fee ) ? $rodeo->office_fee : 0;
                        @endphp
                        <tr class="office-fee-row">
                            <td> &nbsp; </td>
                            <td> 
                                Office fee 
                            </td>
                            <td>
                                @if( $officeFeeApplicable )
                                    $ {{ number_format( ($rodeo->office_fee), 2) }} 
                                @else
                                    <span class="text-muted"> N/A </span>        
                                @endif
                            </td>
                            <td> &nbsp; </td>
                        </tr>                            

                        @php
                            if(  in_array($contestant->id, $addMembershipToContestantIds) )
                            {
                                $membership = $contestant->memberships->where('series_id', $rodeo->series_id)->first();
                                $subtotal += ( $membership  &&  !$membership->paid ) ? $membershipFee : 0;
                            }
                        @endphp
                        @if( in_array($contestant->id, $addMembershipToContestantIds)  &&  !$membership->paid )
                            <tr class="membership-fee-row">                       
                                <td> &nbsp; </td>
                                <td> Membership fee </td>
                                <td>
                                    @if( $contestant->memberships )
                                        $ {{ number_format( $membershipFee, 2) }} 
                                    @else
                                        <span class="text-muted"> N/A </span>        
                                    @endif
                                </td>
                                <td> &nbsp; </td>
                            </tr>
                        @endif

                        <tr class="subtotal-row">
                            <td colspan="2"> &nbsp; </td>
                            <td> $ {{ number_format( ($subtotal ? $subtotal :  0), 2) }} </td>
                            <td> Subtotal </td>
                            <td> &nbsp; </td>
                        </tr>

                        @php
                            $total += $subtotal;
                        @endphp
                    @endforeach

                        <tr>
                            <td colspan="4" class="py-4"> &nbsp; </td>
                        </tr>

                        <tr class="total-row">
                            <td colspan="2"> &nbsp; </td>
                            <td> $ {{ number_format($total, 2) }} </td>
                            <td> Grand total </td>
                        </tr>
                </table>
            </div>


            <div class="card-footer bg-white">

                @if( $rodeoEntries->whereNotNull('check_in_notes')->count() > 0 )
                    <div>
                        <i> Notes </i>
                        <hr class="my-2"> 
                        @foreach( $contestants as $contestant )
                            @php
                                $rodeoEntry = $rodeoEntries->where('contestant_id', $contestant->id)->first();
                            @endphp

                            @if( $rodeoEntry  &&  $rodeoEntry->check_in_notes )
                                <div>{{ $contestant->name }}:</div>
                                <p style="white-space: pre-wrap;">{{ $rodeoEntry->check_in_notes }}</p>                        
                            @endif
                        @endforeach
                    </div>

                    <hr class="my-4">
                @endif


                <form method="POST" action="{{ route('L3.check-in.process', [$organization->id, $rodeo->id]) }}">
                    @csrf
                    @foreach( $contestants as $contestant )
                        <input type="hidden" name="contestants[]" value="{{ $contestant->id }}" />
                    @endforeach
                    @foreach( $addMembershipToContestantIds as $id )
                        <input type="hidden" name="memberships[]" value="{{ $id }}" />
                    @endforeach

                    @php
                        $fee_sum = 0;
                    @endphp


                    <div class="mt-2 mb-4">
                        <label> Paid by </label>
                        <hr class="my-2"> 
                        
                            <label style="font-weight-bold"> Cash </label>
                            <input 
                                type="radio" 
                                id="cash" 
                                name="payment" 
                                value="1" 
                                @foreach ($rodeoEntries as $key => $rodeoEntry)
                                    @if ($rodeoEntry->payment && $rodeoEntry->payment->method == 1)
                                        checked
                                    @endif
                                @endforeach
                                required
                            /> &nbsp;&nbsp;
                            <label style="font-weight-bold"> Check </label>
                            <input 
                                type="radio" 
                                id="check" 
                                name="payment" 
                                value="2" 
                                @foreach ($rodeoEntries as $key => $rodeoEntry)
                                    @if ($rodeoEntry->payment && $rodeoEntry->payment->method == 2)
                                        checked
                                    @endif
                                @endforeach
                                required
                            /> &nbsp;&nbsp;
                            <label style="font-weight-bold"> CC </label>
                            <input 
                                type="radio" 
                                id="cc" 
                                name="payment" 
                                value="3" 
                                @foreach ($rodeoEntries as $key => $rodeoEntry)
                                    @if ($rodeoEntry->payment && $rodeoEntry->payment->method == 3)
                                        checked
                                    @endif
                                @endforeach
                                required
                            /> &nbsp;&nbsp;
                            <label style="font-weight-bold"> Other </label>
                            <input 
                                type="radio" 
                                id="other" 
                                name="payment" 
                                value="4" 
                                @foreach ($rodeoEntries as $key => $rodeoEntry)
                                    @if ($rodeoEntry->payment && $rodeoEntry->payment->method == 4)
                                        checked
                                    @endif
                                @endforeach
                                required
                            /> <br><br>
                                @foreach ($rodeoEntries as $key => $rodeoEntry)
                                    @if ($rodeoEntry->payment && $rodeoEntry->payment->tax)
                                            @php
                                                $fee_sum += $rodeoEntry->payment->tax;
                                            @endphp
                                    @endif
                                @endforeach
                            <label style="font-weight-bold"> Fee </label>
                            <textarea name="fee_sum" class="form-control @error('notes') is-invalid @enderror" >{{$fee_sum}}</textarea>
                            <label style="font-weight-bold"> Total amount </label>
                            <textarea name="total_amount" class="form-control @error('notes') is-invalid @enderror" >{{$total}}</textarea>
                        

                    </div>

                    <hr class="my-4">

                    <div class="mt-2 mb-4">
                        <label> Check-in notes </label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $oldCheckedInNotes) }}</textarea>
                        @error('notes')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>                            
                        @enderror
                    </div>

                    <hr class="my-4">

                    <div class="mb-4">
                        <button class="btn btn-primary"> Check in </button>
                        <a href="{{ route('L3.check-in.contestants', [$organization->id, $rodeo->id]) }}" class="btn btn-outline-secondary"> Cancel </a>
                    </div>

                </form>

            </div>


        </div><!--/card-->
    </div>

{{-- {{json_encode($rodeoEntries)}} --}}
</div>
@endsection