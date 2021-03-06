<?php

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

// Route::get('/', function () {
//     return view('index');
// })->name('index');

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
// Route::get('/difficulty', [App\Http\Controllers\GameController::class, 'displayConfigGame'])->name('difficulty');
Route::post('/new-game', [App\Http\Controllers\GameController::class, 'newGame'])->name('new-game');
Route::get('/play/{game_id}', [App\Http\Controllers\GameController::class, 'displayGame'])->name('play');
Route::get('/result/{game_id}', [App\Http\Controllers\GameController::class, 'displayResults'])->name('results');

Route::post('/next-step', [App\Http\Controllers\GameController::class, 'nextStep'])->name('next-step');
