<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\POSController;
use App\Http\Controllers\ItemController;

// Main Routes
Route::get('/', function () {
    return view('AdminDashboard');
})->name('Admin.dashboard');

Route::get('/pos-dashboard', function () {
    return view('posDashboard');
})->name('pos.dashboard');

// Items Resource Routes
Route::controller(ItemController::class)->prefix('items')->group(function() {
    Route::get('/', 'index')->name('items.index');
    Route::get('/getItems', 'getItems')->name('items.getItems'); // DataTables AJAX route
    Route::post('/', 'store')->name('items.store');
    Route::get('/{id}/edit', 'edit')->name('items.edit');
    Route::put('/{id}', 'update')->name('items.update');
    Route::delete('/{id}', 'destroy')->name('items.destroy');
});
