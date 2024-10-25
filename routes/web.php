<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::resource('tasks', TaskController::class);
Route::post('tasks/update-order', [TaskController::class, 'updateOrder'])->name('tasks.update-order');