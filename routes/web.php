<?php

use App\Http\Controllers\LabelController;
use Illuminate\Support\Facades\Route;
use App\Models\Item;
use App\Models\User;
use App\Models\Label;

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
//     return Redirect::route('items.index');
// });

Route::redirect('/', '/items');

Route::get('/items', function () {
    return view('items.index', [
        'items' => Item::all(),
        'labels' => Label::all(),
        'user_count' => User::count(),
        'label_count' => Label::count(),
        'item_count' => Item::count(), //Item::total()  ??
    ]);
});

Route::get('/items/create', function () {
    return view('items.create');
});

Route::get('/items/x', function () {
    return view('items.show');
});

Route::get('/items/x/edit', function () {
    return view('items.edit');
});

// -----------------------------------------

// Route::get('/categories/create', function () {
//     return view('categories.create');
// });

// Route::get('/categories/x', function () {
//     return view('categories.show');
// });

Route::resource('labels', LabelController::class);

// -----------------------------------------

Auth::routes();
