<?php

use Illuminate\Support\Facades\Route;

//import product controller
use App\Http\Controllers\ProductController;

//route resource for products -> cara singkat utk mendwefinisikan semua route crud dalam satu baris kode tanpa harus satuu2 route::get, route::post dll
Route::resource('/products', ProductController::class);

Route::get('/', function () {
    return view('welcome');
});
