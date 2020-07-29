<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller {

	/**
	 * Create a new AuthController instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware( 'auth:api', array( 'except' => array( 'login' ) ) );
	}

	/**
	 * Get a JWT via given credentials.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function login() {
		$credentials = request( array( 'email', 'password' ) );

		if ( ! $token = $this->guard()->attempt( $credentials ) ) {
			return response()->json( array( 'error' => 'Unauthorized' ), 401 );
		}

		return $this->respondWithToken( $token );
	}

	/**
	 * Get the authenticated User.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function me() {
		return response()->json( $this->guard()->user() );
	}

	/**
	 * Log the user out (Invalidate the token).
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function logout() {
		$this->guard()->logout();

		return response()->json( array( 'message' => 'Successfully logged out' ) );
	}

	/**
	 * Refresh a token.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function refresh() {
		return $this->respondWithToken( $this->guard()->refresh() );
	}

	/**
	 * Get the token array structure.
	 *
	 * @param  string $token
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function respondWithToken( $token ) {
		return response()->json(
			array(
				'access_token' => $token,
				'token_type'   => 'bearer',
				'expires_in'   => $this->guard()->factory()->getTTL() * 60,
			)
		);
	}

	/**
	 * Get the guard to be used during authentication.
	 *
	 * @return \Illuminate\Contracts\Auth\Guard
	 */
	public function guard() {
		return Auth::guard( 'api' );
	}
}
