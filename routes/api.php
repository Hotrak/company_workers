<?php

use Illuminate\Http\Request;
use App\Worker;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::post('/login','UserController@login');
Route::post('/register','UserController@store');

Route::get('/workers/tree', 'WorkerController@tree');
Route::get('/workers/{id}/children', 'WorkerController@treeChildren');

Route::middleware('auth:api')->group(function () {
    Route::get('/user','UserController@index');
    Route::post('/logout', 'UserController@logout');

    Route::get('/workers', 'WorkerController@index');

    Route::post('/workers', 'WorkerController@store');
    Route::put('/workers/{id}', 'WorkerController@update');//+++
    Route::get('/workers/{id}', 'WorkerController@show');
    Route::delete('/workers/{id}', 'WorkerController@destroy');

    Route::get('/positions','PositionController@index');
    Route::post('/positions','PositionController@store');
    Route::put('/positions/{id}','PositionController@update');
});




