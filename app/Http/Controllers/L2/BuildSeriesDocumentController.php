<?php

namespace App\Http\Controllers\L2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Organization;
use App\Series;
use App\Document;

class BuildSeriesDocumentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('level-2');
    }

    public function add( Organization $organization, Series $series )
    {
        if( $organization->id != $series->organization_id )
        {
            abort(403);
        }

        $data['organization'] = $organization;
        $data['series'] = $series;
        $data['documents'] = $organization->documents()->orderBy('name')->get();

        return view('L2.build_series.documents_add', $data);
    }

    public function attach( Request $request, Organization $organization, Series $series )
    {
        if( $organization->id != $series->organization_id )
        {
            abort(403);
        }

        $documentIds = $organization->documents()->pluck('documents.id')->toArray();
        $seriesDocumentIds = $series->documents()->pluck('documents.id')->toArray();

        $validated = $request->validate([
            'documents' => [
                'required', 
                'array'
            ],
            'documents.*' => [
                Rule::in( $documentIds ),
                Rule::notIn( $seriesDocumentIds )
            ]
        ]);

        foreach( $validated['documents'] as $documentId )
        {
            if( !in_array($documentId, $seriesDocumentIds) )
            {
                $series->documents()->attach( $documentId );
            }
        }

        return redirect()
                ->route('L2.build.series.show', [$organization, $series])
                ->with('successAlert', 'Documents updated.');
    }

    public function remove( Request $request, Organization $organization, Series $series, Document $document )
    {
        if( $organization->id != $series->organization_id )
        {
            abort(403);
        }

        if( $organization->id != $document->organization_id )
        {
            abort(403);
        }        

        $series->documents()->detach( $document->id );

        return redirect()
                ->route('L2.build.series.show', [$organization, $series])
                ->with('successAlert', 'Document removed from series.');
    }    
}
