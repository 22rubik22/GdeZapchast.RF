<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Models\User;
use Illuminate\Support\Str;


class CheckSellerPage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->route('id');

        $user = User::with(['legalInfo', 'branches', 'userAddress', 'userPhoneNumber'])
            ->where('user_status', 1)
            ->find($id);

        if (! $user) {
            abort(404, 'Seller not found');
        }

        $request->attributes->set('seller', $user);
        $request->attributes->set('individualPage', true);

        return $next($request);
    }
}
