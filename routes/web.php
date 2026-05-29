<?php

use App\Http\Controllers\MasterCreateController;
use App\Http\Controllers\MasterIndexController;
use App\Http\Controllers\MasterStoreController;
use App\Http\Controllers\RestockItemCompleteController;
use App\Http\Controllers\RestockItemStoreController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/scan');

Route::get('/scan', ScanController::class)->name('scan');
Route::get('/todo', TodoController::class)->name('todo');
Route::get('/master', MasterIndexController::class)->name('master.index');
Route::get('/master/tambah', MasterCreateController::class)->name('master.create');
Route::post('/master', MasterStoreController::class)->name('master.store');
Route::post('/restock-items', RestockItemStoreController::class)->name('restock-items.store');
Route::patch('/restock-items/{restockItem}/complete', RestockItemCompleteController::class)->name('restock-items.complete');
