<?php

use App\Http\Controllers\ApiController\ApiController;
use App\Http\Controllers\ApiController\WorkApiController;
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



Route::get('/getClients', [ApiController::class, 'getClients']);
Route::get('/indexPage', [ApiController::class, 'indexPage']);
Route::get('/showPage/{slug}', [ApiController::class, 'showPage']);
Route::get('/showPost/{slug}/{postSlug}', [ApiController::class, 'showPost']);

//Route::get('/', [ApiController::class, 'indexPage']);
//Route::get('/{slug}', [ApiController::class, 'showPage']);
//Route::get('/{slug}/{postSlug}', [ApiController::class, 'showPost']);

Route::prefix('pages')->group(function (){
    Route::get('/', [ApiController::class, 'indexPage']);
    Route::get('/{slug}', [ApiController::class, 'showPage']);
    Route::get('/{slug}/{postSlug}', [ApiController::class, 'showPost']);
});






Route::get('/main', [WorkApiController::class, 'index']);
Route::get('/works/{slug}', [WorkApiController::class, 'show']);
Route::get('/works/showWorkContent/{slug}', [WorkApiController::class, 'showWorkContent']);

