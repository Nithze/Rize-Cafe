<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Auth;

class Cashier
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if(Auth::user()->usertype != 'cashier'){
        //      return redirect('/');
        // }
        // return $next($request);
        $user = Auth::user();
        
        if ($user->usertype != 'cashier') {
            if ($user->usertype == 'admin') {
                return redirect('admin/admin');
            } elseif ($user->usertype == 'manager') {
                return redirect('manager/managerlog');
            }
            // return redirect('/');
        }
        
        return $next($request);
        
    }
}
