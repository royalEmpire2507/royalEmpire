<?php
use App\Http\Controllers\CustomModuleCRMController;
use App\Http\Controllers\OperationsModuleController;
use Illuminate\Support\Facades\Route;


///----------------------------------CUSTOM -----------------------------------------
Route::get('/clients/list/{pageSize}', [CustomModuleCRMController::class, 'index'])->middleware(['auth'])->name('List|modules|customList|custom');
Route::post('/custom/{module}/add', [CustomModuleCRMController::class, 'store'])->middleware(['auth'])->name('Create|modules|customList|custom');
Route::delete('/custom/{module}/delete', [CustomModuleCRMController::class, 'destroy'])->middleware(['auth'])->name('Delete|modules|customList|custom');
Route::get('/custom/{module}/show', [CustomModuleCRMController::class, 'show'])->middleware(['auth'])->name('List|modules|customList|custom');
Route::put('/clients/update/', [CustomModuleCRMController::class, 'update'])->middleware(['auth'])->name('Create|modules|customList|custom');

///---------------------------------- OPERATIONS -----------------------------------------
Route::get('/operations/list/{idClient}', [OperationsModuleController::class, 'index'])->middleware(['auth'])->name('List|modules|customList|custom');
Route::post('/operations/add', [OperationsModuleController::class, 'store'])->middleware(['auth'])->name('Create|modules|customList|custom');
Route::delete('/operations/delete', [OperationsModuleController::class, 'destroy'])->middleware(['auth'])->name('Delete|modules|customList|custom');
Route::get('/operations/show/{idOperation}', [OperationsModuleController::class, 'show'])->middleware(['auth'])->name('List|modules|customList|custom');
Route::put('/operations/update', [OperationsModuleController::class, 'update'])->middleware(['auth', ])->name('Create|modules|customList|custom');

// ---------- MAIL --------
Route::post('/withdraw', [CustomModuleCRMController::class, 'sendMail']);