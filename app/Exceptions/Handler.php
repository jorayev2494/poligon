<?php

namespace App\Exceptions;

use App\Exceptions\Base\BaseException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Throwable;

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
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {

        });
    }

    /**
     * @param $request
     * @param Throwable $e
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|Response|\Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */
    public function render($request, Throwable $ex)
    {
        if ($ex instanceof ValidationException) {
            $errorData = [
                'error' => 'Bad request',
                'errors' => $ex->errors()
            ];

            return response($errorData, Response::HTTP_BAD_REQUEST);
        }

        if ($ex instanceof BaseException) {
            $errorData = [
                'error' => $ex->getMessage()
            ];

            return response($errorData, $ex->getCode());
        }

        if ($ex instanceof AuthenticationException) {
            $errorData = [
                'error' => $ex->getMessage()
            ];

            return response($errorData, Response::HTTP_UNAUTHORIZED);
        }

        if ($ex instanceof \Exception) {
            $errorData = [
                'error' => $ex->getMessage()
            ];

            return response($errorData, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return parent::render($request, $ex); // TODO: Change the autogenerated stub
    }
}
