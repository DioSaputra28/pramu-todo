<?php

use App\Http\Controllers\HistoryIndexController;
use App\Http\Controllers\HistoryShowController;
use App\Http\Controllers\MasterCreateController;
use App\Http\Controllers\MasterEditController;
use App\Http\Controllers\MasterIndexController;
use App\Http\Controllers\MasterStoreController;
use App\Http\Controllers\MasterUpdateController;
use App\Http\Controllers\RestockItemCompleteController;
use App\Http\Controllers\RestockItemDestroyController;
use App\Http\Controllers\RestockItemStoreController;
use App\Http\Controllers\RestockItemUpdateController;
use App\Http\Controllers\RestockListCompleteController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/scan');

Route::get('/scan', ScanController::class)->name('scan');
Route::get('/todo', TodoController::class)->name('todo');
Route::get('/master', MasterIndexController::class)->name('master.index');
Route::get('/master/tambah', MasterCreateController::class)->name('master.create');
Route::post('/master', MasterStoreController::class)->name('master.store');
Route::get('/master/{product}/edit', MasterEditController::class)->name('master.edit');
Route::patch('/master/{product}', MasterUpdateController::class)->name('master.update');
Route::post('/restock-items', RestockItemStoreController::class)->name('restock-items.store');
Route::patch('/restock-items/{restockItem}/complete', RestockItemCompleteController::class)->name('restock-items.complete');
Route::patch('/restock-items/{restockItem}', RestockItemUpdateController::class)->name('restock-items.update');
Route::delete('/restock-items/{restockItem}', RestockItemDestroyController::class)->name('restock-items.destroy');
Route::post('/restock-lists/complete', RestockListCompleteController::class)->name('restock-lists.complete');
Route::get('/history', HistoryIndexController::class)->name('history.index');
Route::get('/history/{restockList}', HistoryShowController::class)->name('history.show');
