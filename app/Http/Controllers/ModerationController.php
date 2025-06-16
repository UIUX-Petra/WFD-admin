<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ModerationController extends Controller
{
     public function index(Request $request)
    {
        return view('moderation.contents', [
            'initialType' => $request->query('type', 'question')
        ]);
    }
}