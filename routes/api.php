<?php

use App\Http\Controllers\MajorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ambil semua data mahasiswa
Route::get('/students', [StudentController::class, 'index']);

// ambil detail mahasiswa
Route::get('/students/{id}', [StudentController::class, 'show']);

// buat data mahasiswa
Route::post('/students', [StudentController::class, 'store']);

// ubah data mahasiswa
Route::put('/students/{student_number}', [StudentController::class, 'update']);

// hapus data mahasiswa
Route::delete('/students/{id}', [StudentController::class, 'destroy']);

// Jurusan
Route::resource('majors', MajorController::class);
