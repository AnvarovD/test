<?php

use Alifuz\Polylog\Middlewares\RouteLogMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
//    $fp = fsockopen("localhost", 50000, $errno, $errstr, 30);
//    if (!$fp) {
//        echo "$errstr ($errno)<br />\n";
//    } else {
//        fwrite($fp, json_encode(["message" => "The string you want to send"]));
////        while (fgets($fp, 128)) {
////            echo fgets($fp, 128); // If you expect an answer
////        }
//        fclose($fp); // To close the connection
//    }

    return "ok";
})->middleware(RouteLogMiddleware::class);

Route::prefix('logstash')
    ->get('/polylog', static function () {
       $d = \Illuminate\Support\Facades\Http::baseUrl("https://jsonplaceholder.typicode.com")
            ->withMiddleware(new \Alifuz\Polylog\Middlewares\GuzzleLogMiddleware("test", [], 'logstash'))
            ->get("/todos/1")->json();
        return $d;
    })
//->middleware(RouteLogMiddleware::class)
;
