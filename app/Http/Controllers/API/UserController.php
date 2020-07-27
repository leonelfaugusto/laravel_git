<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return response()->json( User::all(), 201 );
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store( Request $request ) {
		$validated = \Validator::make(
			$request->all(),
			array(
				'name'     => 'required',
				'email'    => 'required|email|unique:users',
				'password' => 'required',
			)
		);

		if ( $validated->fails() ) {
			return response()->json( array( 'errors' => $validated->errors() ) );
		}

		$user = new User();

		$user->name     = $request->name;
		$user->email    = $request->email;
		$user->password = Hash::make( $request->password );

		$user->save();

		return response()->json( $user );
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  \App\User $user
	 * @return \Illuminate\Http\Response
	 */
	public function show( User $user ) {
		return response()->json(
			array(
				'status'  => 'success',
				'message' => '',
				'data'    => array(
					'id'   => $user->id,
					'name' => $user->name,
				),
			)
		);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \App\User                $user
	 * @return \Illuminate\Http\Response
	 */
	public function update( Request $request, User $user ) {

		foreach ( $request->all() as $key => $value ) {
			if ( 'password' === $key ) {
				$user->$key = Hash::make( $value );
			} else {
				$user->$key = $value;
			}
		}
		$user->save();

		return response()->json( $user );
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\User $user
	 * @return \Illuminate\Http\Response
	 */
	public function destroy( User $user ) {
		$user->delete();

		return response()->json( null, 204 );
	}
}
