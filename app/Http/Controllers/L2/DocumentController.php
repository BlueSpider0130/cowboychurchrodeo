<?php

namespace App\Http\Controllers\L2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use App\Organization;
use App\Document;


class DocumentController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request, Organization $organization )
    {
        $documents = $organization->documents()->orderBy('name')->get();

        if( $request->wantsJson() )
        {
            return $documents;
        }

        return view('L2.documents.documents_index')
                ->with('organization', $organization)
                ->with('documents', $documents);            
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( Organization $organization )
    {
        return view('L2.documents.documents_create')
                ->with('organization', $organization);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Organization $organization, Request $request )
    {
        $validated = $request->validate([
            'name' => [
                'required', 
                'string', 
                'max:255', 
                ( new \App\Rules\Unique( Document::class ) )->forOrganization( $organization )
            ],

            'description' => [
                'nullable', 
                'string'
            ],

            'file' => [
                'required', 
                'file', 
            ]
        ]);

        $path = $validated['file']->store('documents');
        $filename = $validated['file']->getClientOriginalName();

        try 
        {
            $document = Document::create([
                'organization_id' => $organization->id,
                'name' => $validated['name'], 
                'description' => $validated['description'], 
                'filename' => $filename, 
                'path' => $path,
            ]);           
        } 
        catch ( \Exception $e ) 
        {
            Storage::delete($path);

            throw $e;
        }

        return redirect()
                ->route('L2.documents.index', $organization)
                ->with('successAlert', "Document uploaded.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function show( Organization $organization, Document $document )
    {
        if( !Gate::allows('access-level-2-for-record', $document) )
        {
            abort(403);
        }

        abort( 400 );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function edit( Organization $organization, Document $document )
    {
        if( !Gate::allows('access-level-2-for-record', $document) )
        {
            abort(403);
        }

        return view('L2.documents.documents_edit')
                ->with('organization', $organization)
                ->with('document', $document);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request,  Organization $organization, Document $document )
    {
        if( !Gate::allows('access-level-2-for-record', $document) )
        {
            abort(403);
        }
        
        $validated = $request->validate([
            'name' => [
                'required', 
                'string', 
                'max:255', 
                ( new \App\Rules\Unique( Document::class ) )->forOrganization( $organization )->ignore( $document )
            ],

            'description' => [
                'nullable', 
                'string'
            ],

            'file' => [
                'nullable',
                'file', 
            ]
        ]);


        if( isset($validated['file']) )
        {
            $path = $validated['file']->store('documents');
            $filename = $validated['file']->getClientOriginalName();

            $validated['path'] = $path;
            $validated['filename'] = $filename;

            unset($validated['file']);
        }

        try 
        {
            $document->update( $validated );         
        } 
        catch ( \Exception $e ) 
        {
            if( isset($path)  &&  $path )
            {
                Storage::delete($path);
            }

            throw $e;
        }
      
        return redirect()
                ->route('L2.documents.index', $organization)
                ->with('successAlert', "Document details updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy( Organization $organization, Document $document )
    {
        if( !Gate::allows('access-level-2-for-record', $document) )
        {
            abort(403);
        }

        $document->delete();

        return redirect()
                ->route('L2.documents.index', $organization)
                ->with('successAlert', 'Document removed.');
    }
}
