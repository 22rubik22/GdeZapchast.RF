<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Models\User;
use Illuminate\Support\Str;


class CheckSeller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->route('id');
        $username = $request->route('username');

        $user = User::with(['legalInfo', 'branches', 'userAddress', 'userPhoneNumber'])
            ->where('user_status', 1)
            ->find($id);

        if (! $user) {
            abort(404, 'Seller not found');
        }

        if (Str::slug($user->username) !== $username) {
            abort(400, 'Username mismatch');
        }

        $request->attributes->set('seller', $user);

        return $next($request);
    }
}
