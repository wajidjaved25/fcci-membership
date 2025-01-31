<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class DocumentController extends Controller
{
    public function show($id)
{
    $document = Document::find($id);

    if (!$document) {
        abort(404, 'Document not found');
    }

    return view('supervisor.documents.show', compact('document'));
}
}
