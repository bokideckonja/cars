<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }

    /**
     * Prepisujem unauthenticated metod, kako bih naveo razlicite 
     * putanje za redirekciju zavisno da li je member ili admin
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    // protected function unauthenticated($request, AuthenticationException $exception)
    // {
    //     // Ako ocekuje json, samo vrati json response
    //     if ($request->expectsJson()) {
    //         return response()->json(['message' => $exception->getMessage()], 401);
    //     }

    //     // Odredi rutu za redirekciju, na osnovu guard-a
    //     // Ako ne postoji, postavi default 'login'
    //     $guard = array_get($exception->guards(), 0);

    //     switch ($guard) {
    //         case 'admin':
    //             $login = 'admin/login';
    //             break;
    //         case 'users':
    //             $login = 'login';
    //             break;
    //         default:
    //             $login = 'login';
    //             break;
    //     }

    //     return redirect()->guest($login);
    // }
}
