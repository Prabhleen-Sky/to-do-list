<?php

use App\Http\Controllers\TaskController;
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
    return view('welcome');
});

Route::get('/tasks', [TaskController::class,'index']);
Route::post('/create-new-task', [TaskController::class,'storeTask'])->name('store.task');
Route::get('/delete-task/{id}', [TaskController::class,'deleteTask'])->name('delete.task');
Route::put('/edit-task', [TaskController::class,'editTask'])->name('edit.task');


Route::get('/test-database-connection', function () {
    try {
        DB::connection()->getPdo();
        return "Connected to the database!";
    } catch (\Exception $e) {
        return "Unable to connect to the database. Error: " . $e->getMessage();
    }
});