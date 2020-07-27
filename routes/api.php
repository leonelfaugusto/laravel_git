<?php

use Illuminate\Http\Request;
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
Route::apiResource( 'users', 'API\UserController' );


Route::fallback(
	function() {
		return response()->json(
			array(
				'status'  => 'error',
				'message' => 'Not Found.',
				'data'    => false,
			),
			404
		);
	}
)->name( 'fallback.error.404' );

