<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Throwable;

class Handler extends ExceptionHandler {

	/**
	 * A list of the exception types that are not reported.
	 *
	 * @var array
	 */
	protected $dontReport = array();

	/**
	 * A list of the inputs that are never flashed for validation exceptions.
	 *
	 * @var array
	 */
	protected $dontFlash = array(
		'password',
		'password_confirmation',
	);

	/**
	 * Report or log an exception.
	 *
	 * @param  \Throwable $exception
	 * @return void
	 *
	 * @throws \Exception
	 */
	public function report( Throwable $exception ) {
		parent::report( $exception );
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Throwable               $exception
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @throws \Throwable
	 */
	public function render( $request, Throwable $exception ) {

		if ( $exception instanceof AuthenticationException ) {
			return response()->json(
				array(
					'status'  => 'error',
					'message' => $exception->getMessage(),
				),
				401
			);
		}

		if ( $exception instanceof ModelNotFoundException && $request->is( 'api/*' ) ) {
			return response()->json(
				array(
					'status'  => 'error',
					'error'   => 'Entry for ' . str_replace(
						'App\\',
						'',
						$exception->getModel()
					) . ' not found',
					'message' => $exception->getMessage(),
				),
				404
			);
		}

		return parent::render( $request, $exception );
	}
}
