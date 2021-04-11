<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\GroupController;

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


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('tasks', 'App\Http\Controllers\Api\TaskController@index');
Route::get('statuses', 'App\Http\Controllers\Api\StatusController@index');
Route::post('addtask', 'App\Http\Controllers\Api\TaskController@store');
Route::delete('deletetask/{id}', 'App\Http\Controllers\Api\TaskController@destroy');
Route::put('editTask/{id}', 'App\Http\Controllers\Api\TaskController@update');
Route::put('tasks/sync', 'App\Http\Controllers\Api\TaskController@sync')->name('tasks.sync');
