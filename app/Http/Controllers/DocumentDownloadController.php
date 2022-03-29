<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Document;

class DocumentDownloadController extends Controller
{
    public function download( Document $document )
    {
        $filename = $document->filename  ?  $document->filename  :  basename($document->path);

        return Storage::download( $document->path, $filename );
    }
}
