<?php

use App\Http\Controllers\UserController;
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

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/users', [UserController::class, 'showAll'])->middleware(['auth'])->name('users');
Route::get('/user_cep/{CEP}', [UserController::class, 'getCEP'])->middleware(['auth'])->name('users.cep');
Route::get('/user/{id}', [UserController::class, 'show'])->middleware(['auth'])->name('user');
Route::get('/user_update/{id}', [UserController::class, 'update'])->middleware(['auth'])->name('user.update');
Route::post('/user_update/{id}', [UserController::class, 'store'])->middleware(['auth'])->name('user.set_user');
Route::post('/user_update_password/{id}', [UserController::class, 'updatePassword'])->middleware(['auth'])->name('user.password');
Route::get('/user_active/{id}/{key}', [UserController::class, 'updateActive'])->middleware(['auth'])->name('user.active');


require __DIR__.'/auth.php';
