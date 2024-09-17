<?php
// <?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\ActivityLog;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // Catat aktivitas login jika autentikasi berhasil
        if (Auth::check()) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'activity_type' => 'login',
                // Tambahkan detail lain yang relevan, seperti timestamp, IP address, dll.
            ]);
        }

        $request->session()->regenerate();

        if ($request->user()->usertype === 'admin') {
            return redirect('admin/admin');
        }
        if ($request->user()->usertype === 'cashier') {
            return redirect('cashier/cashier');
        }
        if ($request->user()->usertype === 'manager') {
            return redirect('manager/managerlog');
        }

        return redirect()->intended(route('dashboard'));

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Catat aktivitas logout sebelum logout dilakukan
        if (Auth::check()) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'activity_type' => 'logout',
                // Tambahkan detail lain yang relevan, seperti timestamp, IP address, dll.
            ]);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}


// namespace App\Http\Controllers\Auth;

// use App\Http\Controllers\Controller;
// use App\Http\Requests\Auth\LoginRequest;
// use Illuminate\Http\RedirectResponse;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\View\View;

// class AuthenticatedSessionController extends Controller
// {
//     /**
//      * Display the login view.
//      */
//     public function create(): View
//     {
//         return view('auth.login');
//     }

//     /**
//      * Handle an incoming authentication request.
//      */
//     public function store(LoginRequest $request): RedirectResponse
//     {
//         $request->authenticate();

//         $request->session()->regenerate();

//         if ($request->user()->usertype === 'admin') {
//             return redirect('admin/admin');
//         }
//         if ($request->user()->usertype === 'cashier') {
//             return redirect('cashier/cashier');
//         }
//         if ($request->user()->usertype === 'manager') {
//             return redirect('manager/managerlog');
//         }

//         return redirect()->intended(route('dashboard'));
//     }

//     /**
//      * Destroy an authenticated session.
//      */
//     public function destroy(Request $request): RedirectResponse
//     {
//         Auth::guard('web')->logout();

//         $request->session()->invalidate();

//         $request->session()->regenerateToken();

//         return redirect('/login');
//     }
// }
