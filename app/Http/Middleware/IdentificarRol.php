<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IdentificarRol
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        
        if(auth()->user()->rol != 'Administrador'){
            return response([
                "status" => 0,
                "msg" => "No estas autorizado",
            ], 401);
        }
        return $next($request);
    }
}
