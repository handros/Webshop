<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\MessageController;
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

// Route::get('/', [ItemController::class, 'index'])->name('home');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/users', [HomeController::class, 'users'])->name('users');
Route::get('/search', [ItemController::class, 'search'])->name('items.search');


// -----------------------------------------


Route::resource('items', ItemController::class);
Route::resource('labels', LabelController::class);
Route::resource('comments', CommentController::class);
Route::resource('auctions', AuctionController::class);
Route::resource('orders', OrderController::class);
Route::resource('bids', BidController::class);
Route::resource('messages', MessageController::class);
Route::get('/auctions/create/{item}', [AuctionController::class, 'create'])->name('auctions.create');

// -----------------------------------------


Auth::routes();
