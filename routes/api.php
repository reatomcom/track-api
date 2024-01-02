<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Activities;
use App\Http\Controllers\Screenshots;

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
