<?php

namespace App\Exceptions;

use App\Http\Controllers\Api\V1\Controller;
use App\Http\Controllers\Api\V1\ResultType;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Mockery\Exception\InvalidOrderException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (NotFoundHttpException $e, $request) {
            return (new Controller)->apiResponse(ResultType::NotFound, null, 404);
        });

        $this->renderable(function (AuthenticationException $e, $request) {
            return response(['success' => false, 'message' => 'Unauthenticated']);
        });

        $this->renderable(function (ModelNotFoundException $e, $request) {
            return (new Controller)->apiResponse(ResultType::Custom, $e->getModel().'not found', 404);
        });

        $this->renderable(function (ValidationException $e, $request) {
            return response(['success' => false, 'messages' => $e->errors()]);
        });
    }
}
