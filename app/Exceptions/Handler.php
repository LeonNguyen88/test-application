<?php

namespace App\Exceptions;

use App\Supports\Traits\HandleErrorException;
use Flugg\Responder\Exceptions\ConvertsExceptions;
use Flugg\Responder\Exceptions\Http\HttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ConvertsExceptions;
    use HandleErrorException;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
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
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    public function render($request, Throwable $e): \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
    {
        if ($request->is('api/*')) {
            if ($e instanceof HttpException) {
                return $this->renderResponse($e);
            } elseif ($e instanceof ValidationException) {
                return $this->renderApiResponse($e);
            } elseif ($e instanceof NotFoundHttpException) {
                return $this->renderApiNotFoundResponse($e);
            } elseif ($e instanceof BadRequestHttpException) {
                return $this->renderApiBadRequestResponse($e);
            } elseif ($e instanceof \ErrorException) {
                return $this->renderServerErrorException($e);
            }
        }
        return parent::render($request, $e);
    }
}
