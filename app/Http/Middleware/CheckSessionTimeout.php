<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
class CheckSessionTimeout
{

    protected $timeout = 300;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Session::has('lastActivityTime')){
            $inactivity = time() - Session::get('lastActivityTime');
            if ($inactivity > $this->timeout){
                Session::forget('lastActivityTime');
                Auth::logout();
                Session::invalidate();
                Session::regenerateToken();

                return redirect('/')->with('warning', 'Tu sesión ha experidao por inactividad');
            }
        }

        Session::put('lastActivityTime', time());

        return $next($request);
    }
}
