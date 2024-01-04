<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\TrackLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TrackLogs extends Controller
{
    public function log(Request $request)
    {
        Log::info($request->input());
        
        $trackLogTimestamp = $request->input('timestamp');
        $trackLogDateTime = Carbon::createFromTimestamp($trackLogTimestamp);
        
        $logDiff = null;

        $previousTrackLog = TrackLog::where('client_id', $request->client->id)
            ->where('user_id', $request->user->id)
            ->whereNull('to')
            ->orderBy('id', 'desc')
            ->first();

        if ($previousTrackLog) {
            $previousTrackLogTimestamp = Carbon::parse($previousTrackLog->from)->timestamp;
            $logDiff = $trackLogTimestamp - $previousTrackLogTimestamp;
            $previousTrackLog->to = $trackLogDateTime;
            $previousTrackLog->seconds = $logDiff;
            $previousTrackLog->save();
        }

        $trackLog = new TrackLog;
        $trackLog->client_id = $request->client->id;
        $trackLog->user_id = $request->user->id;
        $trackLog->from = $trackLogDateTime;
        $trackLog->title = $request->input('title');
        $trackLog->productivity = 100; // ????
        $trackLog->save();

        return [
            'ok' => true
        ];
    }
}
