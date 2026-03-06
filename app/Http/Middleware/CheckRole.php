<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $role
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {
        //check if user is authenticated
        if (!auth()->check()) {
            return redirect('/');
        }

        /** @var \App\Models\User $user */
        $user = auth()->user();

        //check if user has the required role
        if (!$user->hasRole($role) && $user->primary_role !== $role) {
            abort(403, 'Unauthorized access');
        }

        return $next($request);
    }
}
