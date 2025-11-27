<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AfterLoginController extends Controller
{
    public function handle(Request $request)
    {
        $user = $request->user();

        // Por seguridad, si no hay user redirige al login
        if (! $user) {
            return redirect()->route('login');
        }

        // Prioriza roles según necesidad
        if ($user->hasRole('admin')) {
            return redirect()->intended(route('dashboard'));
        }

        // Usuario "player" o cualquier otro
        return redirect()->intended(route('player'));
    }
}
