<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Login
Route::post('/login','AuthController@postLogin');
// Route::middleware('auth:api')->group(function () {
    Route::middleware('APIToken')->group(function () {
    Route::post('/create-task','ToDoTaskController@createTask'); // Create To Do Items or Task
    Route::put('/update-task/{task_id}','ToDoTaskController@updateTask'); // Update To Do Items or Task
    Route::get('/tasks', "ToDoTaskController@listTasks"); // Task Listing
    Route::delete("task/{task_id}", "ToDoTaskController@deleteTask");
    Route::post('/logout','AuthController@postLogout'); // Logout
  });
// });
