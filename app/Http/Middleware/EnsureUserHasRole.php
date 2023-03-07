<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $roleId)
    {
        if ($request->user()->roles()->where('id', '>=', $roleId)->exists()) return $next($request);

        return Redirect::route('home')->with('message', 'Votre inscription doit être validée par un administrateur pour que vous puissiez accéder au reste du site.');
    }
}
