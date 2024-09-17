<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Auth;

class Manager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if(Auth::user()->usertype != 'manager'){
        //      return redirect('/');
        // }
        // return $next($request);
        $user = Auth::user();
        
        if ($user->usertype != 'manager') {
            if ($user->usertype == 'cashier') {
                return redirect('cashier/cashier');
            } elseif ($user->usertype == 'admin') {
                return redirect('admin/admin');
            }
            // return redirect('/');
        }
        
        return $next($request);
    }
}
