<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View; // Tambahkan ini
use Illuminate\Http\RedirectResponse; // Tambahkan ini

class AuthenticatedSessionController extends Controller
{
    /**
     * Menampilkan halaman login untuk Web (Staff).
     * Dibutuhkan karena stack API tidak menyertakan ini.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): Response|RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // JIKA request bukan dari API/AJAX (berarti dari Form Web)
        if (!$request->wantsJson()) {
            return redirect()->intended(route('dashboard'));
        }

        // Tetap pertahankan fungsi lama untuk API/Flutter
        return response()->noContent();
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response|RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // JIKA request dari Browser/Web
        if (!$request->wantsJson()) {
            return redirect('/');
        }

        // Tetap pertahankan fungsi lama untuk API/Flutter
        return response()->noContent();
    }
}
