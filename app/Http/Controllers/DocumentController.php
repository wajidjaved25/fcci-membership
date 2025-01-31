<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class DocumentController extends Controller
{
    public function viewDocument($filename)
    {
        $filePath = "public/{$filename}";

        if (!Storage::exists($filePath)) {
            abort(404, 'File not found');
        }

        // Check if the user is authorized to view the document (optional security check)
        if (!auth()->check()) {
            abort(403, 'Unauthorized');
        }

        // Return the file as a response
        return response()->file(storage_path("app/{$filePath}"));
    }
}
