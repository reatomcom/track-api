<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Activities extends Controller
{
    public function store(Request $request)
    {
        // Log input to the console
        Log::info($request->input());
        // error_log($request->input());
        return [
            'ok' => true,
            'activity' => $request->input()
        ];
    }
}
