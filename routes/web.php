<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BundesligaController;

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

Route::get('calculatebundesliga', [BundesligaController::class, 'calculateEloRanking']);
Route::get('flourish', [BundesligaController::class, 'generateCSVForFlourish']);

Route::get('test', function() {
    dd('test');
});
