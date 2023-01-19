<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|null
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        $user = User::where('email', $request->email)->first();
        if (! $user ||
            ($user instanceof MustVerifyEmail &&
            ! $user->hasVerifiedEmail())) {
            return response()->json([
                "message" => 'Your email address is not verified.',
            ], 403);
        }

        return $next($request);
    }
}
