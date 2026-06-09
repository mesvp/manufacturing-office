<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {

            if ($guard=='admin' && Auth::guard($guard)->check()) {
                return redirect(RouteServiceProvider::HOME);
            }

            if (Auth::guard($guard)->check()) 
            {
                $res=User::where('id',auth()->user()->id)->first();
   
                if($res->role!='student')
                {
                    return redirect()->intended(RouteServiceProvider::EMPLOYER_HOME);
                }
                else
                {
                    return redirect()->intended(RouteServiceProvider::HOME);
                }
            }
        }

        return $next($request);
    }
}
