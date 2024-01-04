<?php

use Illuminate\Http\Request;
use App\Http\Controllers\UserAuth;
use App\Http\Controllers\TrackLogs;
use App\Http\Controllers\Activities;
use App\Http\Controllers\Screenshots;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::get('/', function () {
    return [
        'ok' => true,
        'message' => 'Welcome to the API'
    ];
});
Route::post('/activities',  [Activities::class, 'store']);
Route::post('/screenshots',  [Screenshots::class, 'store']);
Route::post('/screenshots/log',  [Screenshots::class, 'log'])->middleware('apiAuth');
Route::post('/tracklogs',  [TrackLogs::class, 'log'])->middleware('apiAuth');
Route::post('/auth/login',  [UserAuth::class, 'login']);
Route::post('/auth/logout',  [UserAuth::class, 'logout']);
