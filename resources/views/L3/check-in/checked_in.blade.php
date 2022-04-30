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

        <h1> {{ $rodeo->name ? $rodeo->name : 'Rodeo #' . $rodeo->id }} </h1>

        <ul class="nav nav-tabs mt-4 mb-4">
            <li class="nav-item">
                <a class="nav-link"
                    href="{{ route('L3.check-in.contestants', [$organization->id, $rodeo->id]) }}">Check in</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="#">Checked in</a>
            </li>
        </ul>

        <div class="mt-5">
            <h2> Checked in </h2>
            @if ($checkedInEntries->count() < 1)
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
                        @foreach ($checkedInEntries as $key => $entry)
                            <tr>
                            @php
                                $officefee = 0;
                                $total = 0;
                            @endphp
                                <div class="modal fade modal-side modal-top-right" id="{{$key}}" data-keyboard="false"
                                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">
                                                    {{ $rodeo->name ? $rodeo->name : 'Rodeo #' . $rodeo->id }} </h5>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                            <div class="modal-body">
                                                {{ $rodeo->starts_at->format('l') }},{{$rodeo->starts_at->format('j F, Y')}}
                                                <hr class="mb-1">
                                                {{ $entry->contestant->lexical_name_order }}
                                                <i class="far fa-check-circle"></i>
                                                @foreach ($entries as $Mentry)
                                                    @if ($Mentry->contestant_id == $entry->contestant_id)
                                                        <hr class="mb-1">
                                                        @if ($Mentry->competition->group->name != "PEE WEE")
                                                            @php
                                                                $officefee = 10;
                                                            @endphp
                                                        @endif
                                                        <div style="display: flex; justify-content: space-between;">
                                                            <div>
                                                                {{$Mentry->competition->group->name}} {{$Mentry->competition->event->name}}
                                                            </div>
                                                            <div>
                                                                $ {{$Mentry->competition->entry_fee}}
                                                                @php
                                                                    $total += $Mentry->competition->entry_fee;
                                                                @endphp
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                                <hr class="mb-1">
                                                <div style="display: flex; justify-content: space-between;">
                                                    <div>
                                                        Office fee
                                                    </div>
                                                    <div>
                                                        $ {{ $officefee }}
                                                        @php
                                                            $total += $officefee;
                                                        @endphp
                                                    </div>
                                                </div>
                                                <hr class="mb-3" style="height:5px;">
                                                {{-- <i class="far fa-check-circle"></i> --}}
                                            </div>
                                            <div class="modal-footer">
                                                <div style="width:100%; display: flex; justify-content: space-between;">
                                                    <div>
                                                        Total
                                                    </div>
                                                    <div>
                                                        $ {{ $total }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <td onclick="modal({{$key}})">
                                    {{ $entry->contestant->lexical_name_order }}
                                </td>
                                <td>
                                    @if ($entry->contestant->sex)
                                        <img src="/assets/{{ $entry->contestant->sex }}.png">
                                    @else
                                        ---
                                    @endif
                                </td>
                                <td>
                                    @if ($rodeo->series_id)
                                        <x-membership-badge :contestant="$entry->contestant" :series="$rodeo->series_id" class="pl-3" />
                                    @endif
                                </td>
                                <td> {{ $entry->check_in_notes }} </td>
                                <td> {{ $entry->checked_in_notes }} </td>
                                <td class="text-md-center">
                                    <form method="post"
                                        action="{{ route('L3.check-in.destroy', [$organization->id, $entry->id]) }}">
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

        {{-- ---------------------------------------------------------------- --}}


        <script type="application/javascript">
            console.log("sdf");
            function modal(key) {
                console.log(key);
                $('#' + key).modal('show');
            }
        </script>
    @endsection
