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
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // return $request->expectsJson()
        //             ? response()->json(['message' => $exception->getMessage()], 401)
        //             : redirect()->guest(route('login'));


        if ($request->expectsJson()) {
            return response()->json(['message' => $exception->getMessage()], 401);
        }

        // return redirect()->guest('login');

        // Customize the redirect based on the guard
        // Note that we don't know which guard failed here, but I can't find an elegant way
        // to handle this and I know in this project I am only using one guard at a time anyway.
        $middleware = request()->route()->gatherMiddleware();

        // return dd($middleware);

        $guard = config('auth.defaults.guard');

        foreach($middleware as $m) {
            if(preg_match("/auth:/",$m)) {
                list($mid, $guard) = explode(":",$m);
            }
        }

        switch($guard) {
            case 'admin':
                $login = 'admin/login';
                break;
            default:
                $login = 'login';
                break;
        }

        return redirect()->guest($login);
        // return redirect($login);
    }
}
