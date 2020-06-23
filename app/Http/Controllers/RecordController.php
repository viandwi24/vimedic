<?php

namespace App\Http\Controllers;

use App\Models\Record;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    public function show($code)
    {
        $record = Record::with('doctor', 'patient', 'recipe')
            ->where('code', $code)->firstOrFail();
        return view('pages.record', compact('record'));
    }
}
