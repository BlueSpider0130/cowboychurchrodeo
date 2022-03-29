@extends('layouts.producer')

@section('content')
<div class="container-fluid">

    <x-session-alerts />

    <h1> {{ $series->name }} </h1>
    <hr>
    <div class="row">
        <div class="col-12 col-md-10 col-lg-8">      

                <div class="card mt-3 mb-5">
                    <div class="card-body">
                        Series: {{ $series->name }}
                        <hr class="my-2">
                        <div>
                            {{ $series->starts_at ? $series->starts_at->toFormattedDateString() : 'TBA'}} 
                            &ndash; 
                            {{ $series->ends_at ? $series->ends_at->toFormattedDateString() : 'TBA' }}
                        </div>         
                    </div>
                </div><!--/card-->

                <h2 class="mt-5"> Add documents to series </h2>
                <hr>
                @if( $documents->count() < 1 )
                    <p>There are no more documents that you can add to the series. You can upload organization documents in the settings section.</p>
                @endif

                <form method="post" action="{{ route('L2.build.series.documents.attach', [$organization, $series]) }}" disabled>
                    @csrf

                    <table class="table bg-white border rounded table-responsive-cards">
                        <tbody>
                            @foreach( $documents as $document )
                                @php
                                    $disabled = $series->documents->where('id', $document->id)->count() ? true : false; 
                                @endphp
                                <tr class="{{ $disabled ? 'text-secondary' : '' }}"> 
                                    <td>
                                        @if( !$disabled ) 
                                            <input type="checkbox" id="checkbox-{{ $document->id }}" name="documents[]" value="{{ $document->id }}"> 
                                        @endif
                                    </td>
                                    <td onclick="var el = document.getElementById('checkbox-{{ $document->id }}'); el.checked = el.checked ? false : true;">
                                        {{ $document->name }} 
                                    </td> 
                                    <td onclick="var el = document.getElementById('checkbox-{{ $document->id }}'); el.checked = el.checked ? false : true;">
                                        {{ $document->filename }}
                                    </td> 
                                    <td>
                                        @if( $disabled ) <i>Already added to series</i> @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <hr>
                    @if( $documents->count() != $series->documents->count() )
                        <x-form.buttons submit-name="Add" :cancel-url="route('L2.build.series.show', [$organization, $series])" />
                    @endif

                </form>


        </div><!--/col-->
    </div><!--/row-->
</div>
@endsection
