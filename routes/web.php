<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TvDisplayController;


Route::get('/tv', [TvDisplayController::class, 'index']);
Route::get('/tv/data', [TvDisplayController::class, 'data']);
Route::post('/tv/repeat', [TvDisplayController::class, 'repeat']);

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/tts/latest', function () {
//     return response()->json([
//         'audio' => cache()->pull('last_audio')
//     ]);
// });
