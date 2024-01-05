<?php

namespace App\Http\Controllers;

use Cache;
use Carbon\Carbon;
use App\Models\Apps;
use App\Models\Host;
use App\Models\TrackLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TrackLogs extends Controller
{
    public function log(Request $request)
    {
        Log::info($request->input());
        // Timestamp TO and Seconds
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

        // App_id
        $app = Cache::remember('app_' . $request->client->id . '_' . $request->input('app') . "_" . $request->input('host'), 60 * 60 * 24, function () use ($request) {
            $app = Apps::where('client_id', $request->client->id)
                ->where('name', $request->input('app'))
                ->first();
            if (!$app) {
                $app = new Apps;
                $app->client_id = $request->client->id;
                $app->name = $request->input('app');
                $app->save();
            }
            return $app;
        });

        // Host_id

        $host = Cache::remember('host_' . $request->client->id . '_' . $request->input('host'), 60 * 60 * 24, function () use ($request) {
            $host = Host::where('client_id', $request->client->id)
                ->where('name', $request->input('host'))
                ->first();
            if (!$host) {
                $host = new Host;
                $host->client_id = $request->client->id;
                $host->name = $request->input('host');
                $host->save();
            }
            return $host;
        });

        $trackLog = new TrackLog;
        $trackLog->client_id = $request->client->id;
        $trackLog->user_id = $request->user->id;
        $trackLog->from = $trackLogDateTime;
        $trackLog->app_id = $app->id;
        $trackLog->title = $request->input('title');
        $trackLog->host_id = $host->id;
        $trackLog->productivity = 100; // ????
        $trackLog->save();

        return [
            'ok' => true
        ];
    }
}
