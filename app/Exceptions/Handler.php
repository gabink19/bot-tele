<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Throwable $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $e)
    {
        $parentRender = parent::render($request, $e);

        $message = 'Server Error';

        if($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            $message = 'Halaman Tidak Ditemukan.';
        }

        if($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
            $message = 'Method Tidak Diizinkan.';
        }

        if($e instanceof HttpException && $e->getStatusCode() == 403){
            $message = 'Akses Ditolak.';
        }

        if($e instanceof HttpException && $e->getStatusCode() == 401){
            $message = 'Token Akses anda tidak sah.';
        }

        $rc = ($parentRender->status() && $parentRender->status() !== 0) ? $parentRender->status() : 500;
        if ($e->getMessage() !== '' && $this->isJson($e->getMessage())) {
            $message = json_decode($e->getMessage(),1);
        }else if ($e->getMessage() !== '') {
            $message = $e->getMessage();
        }
        $responseBody = [
            "errors" => [
                "status" => $rc,
                "source" => ["pointer" =>  $request->getPathInfo()],
                "message" => $message,
            ]
        ];
        if (!is_array($message) && strpos($message, 'cURL error 28') !== false) {
            $responseBody = [
                "errors" => [
                    "status" => 408,
                    "source" => ["pointer" =>  $request->getPathInfo()],
                    "message" => 'Connection timed out.',
                ]
            ];
            return response()->json($responseBody, 408);
        }
        if ($rc==400) {
            $responseBody = [
                "errors" => [
                    "status" => $rc,
                    "source" => ["pointer" =>  $request->getPathInfo()],
                    "message" => $message,
                ]
            ];
            return response()->json($responseBody, 200);
        }

        if(App::environment(['local'])){
            $responseBody['line'] = $e->getLine();
            $responseBody['File'] = $e->getFile();
            $responseBody['stactrace'] = $e->getTraceAsString();
        }

        return response()->json($responseBody, (int) $rc);
    }

    function isJson($string) {
       json_decode($string);
       return json_last_error() === JSON_ERROR_NONE;
    }

}
