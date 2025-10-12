<?php

namespace App\Http\Controllers;

use App\Models\Student\Standard;
use Illuminate\Http\Request;

class StandardController extends Controller
{
    public function index()
    {
        return response()->json(Standard::orderBy('order')->get());
    }
}
