<?php
// app/Http/Middleware/CheckUserActive.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserActive
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && !Auth::user()->is_active) {
            Auth::logout();
            return redirect()->route('filament.admin.auth.login')
                ->with('error', 'Your account has been deactivated. Please contact administrator.');
        }

        return $next($request);
    }
}