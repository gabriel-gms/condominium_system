<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function getAll(){
        $array['error'] = false;

        $documents = Document::all();
        foreach($documents as $documentKey => $documentValue){
            $documents[$documentKey]['fileurl'] = asset('storage/'.$documentValue['fileurl']);
        }

        $array['documents'] = $documents;

        return response()->json($array);
    }
}
