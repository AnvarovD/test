<?php

use App\Http\Controllers\ApiController\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/indexPage', [ApiController::class, 'indexPage']);
Route::get('/showPage/{slug}', [ApiController::class, 'showPage']);
Route::get('/getClients', [ApiController::class, 'getClients']);
Route::get('/showPost/{slug}/{postSlug}', [ApiController::class, 'showPost']);
