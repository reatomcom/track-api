<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Screenshots extends Controller
{
    public function store(Request $request)
    {
        // Save scrheenshot file to storage

        $input = $request->all();
        $file = $request->file('screenshot');
        $filename = date('Y-m-d-H-i-s');
        $file->storeAs('screenshots', $filename . '.png');


        // Log input to the console
        Log::info(["screenshot" => true]);
        // error_log($request->input());
        return [
            'ok' => true,
            'input' => $request->input()
        ];
    }
}
