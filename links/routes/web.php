<?php

use App\Http\Controllers\LinksController;
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
//     return view('home');
// });

// Auth::routes();

// Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/', [LinksController::class, 'show'])->name('show.link');
Route::post('/', [LinksController::class, 'generate'])->name('generate.link');
Route::get('{short_link}', [LinksController::class, 'shortLink'])->name('short.link');