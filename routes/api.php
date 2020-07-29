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

Route::group(
	array(

		'middleware' => 'api',
		'prefix'     => 'auth',

	),
	function ( $router ) {

		Route::post( 'login', 'API\AuthController@login' )->name( 'login' );
		Route::post( 'logout', 'API\AuthController@logout' );
		Route::post( 'refresh', 'API\AuthController@refresh' );
		Route::post( 'me', 'API\AuthController@me' );

	}
);


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

