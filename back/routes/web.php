<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConfigController;

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
    return response()->json(["res"=>'hola']);
});

include_once('Custom/routes.php');
include_once('auth.php');


// Route::get('/cache', function() {
//     $exitCode = Artisan::call('route:cache');
//     $exitCode1 = Artisan::call('view:cache');
//     $exitCode2 = Artisan::call('config:cache');
//     return 'cache cleared';
// });
