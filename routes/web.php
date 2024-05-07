<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckUploadSize;

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

// Route::redirect('/', '/items');
//Route::get('/', [ItemController::class, 'index']);
Route::get('/', [HomeController::class, 'index']);

// -----------------------------------------


Route::resource('items', ItemController::class);
Route::resource('labels', LabelController::class);
Route::resource('comments', CommentController::class);

// -----------------------------------------


Auth::routes();
