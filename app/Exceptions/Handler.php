<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Illuminate\Auth\AuthenticationException;
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
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        if (request()->is('api/v1/*')){
            $api = new \App\Classes\CustomMainErrors;

            $this->renderable(function (NotFoundHttpException $e, $request) use ($api){
                return $api->error(['error' => trans('code.404')]);
            });
//
//            $this->renderable(function (TooManyRequestsHttpException $e, $request) use ($api){
//                return $api->error(null, trans('code.403'), 403);
//            });
//
//            $this->renderable(function (\ErrorException $e, $request) use ($api){
//                return $api->error(null, trans('code.500'), 403);
//            });
//
//            $this->renderable(function (\BadMethodCallException $e, $request) use ($api){
//                return $api->error(null, trans('code.500'), 403);
//            });
//
//            $this->renderable(function (MethodNotAllowedHttpException $e, $request) use ($api){
//                return $api->error(null, trans('code.500'), 403);
//            });
//
//            $this->renderable(function (UnauthorizedHttpException $e, $request) use ($api){
//                return $api->error(null, trans('code.500'), 403);
//            });
//
//            $this->renderable(function (AuthenticationException $e, $request) use ($api){
//                return $api->error(null, trans('code.500'), 403);
//            });
        }
    }
}
