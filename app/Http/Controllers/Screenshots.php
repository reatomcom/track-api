<?php

namespace App\Http\Controllers;

use App\Models\Screenshot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Screenshots extends Controller
{
    public function store(Request $request)
    {
        // Save scrheenshot file to storage
        $input = $request->all();
        $file = $request->file('screenshot');
        $filename = $file->getClientOriginalName();
        //$filename = date('Y-m-d-H-i-s');
        $file->storeAs('screenshots', $filename . '.png');


        // Log input to the console
        Log::info(["screenshot" => true]);
        // error_log($request->input());
        return [
            'ok' => true,
            'input' => $request->input()
        ];
    }

    public function log(Request $request)
    {
        $fileName = $request->input('fileName');

        $fsConfig = config('filesystems.default');
        //$fsPath = config('filesystems.disks.' . $fsConfig . '.root');
        $fullPath = '/' . $fileName;

        $screenshot = new Screenshot;
        $screenshot->client_id = $request->client->id;
        $screenshot->user_id = $request->user->id;
        $screenshot->device_id = $request->device->id;
        $screenshot->fs = $fsConfig;
        $screenshot->path = $fullPath;
        $screenshot->save();

        return [
            'ok' => true,
            'input' => $request->input(),
            'screenshot' => $screenshot,
        ];
    }
}
