<?php

namespace Intendant\{$stub_intendant_zone_upper}\Middleware;

use Closure;
use Intendant\{$stub_intendant_zone_upper}\Facades\Incore;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if (Auth::guard(Incore::getModuleZone())->guest() && !$this->shouldPassThrough($request)) {
            return redirect()->guest(Incore::url('auth/login'));
        }

        return $next($request);
    }

    /**
     * Determine if the request has a URI that should pass through verification.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function shouldPassThrough($request)
    {
        $excepts = [
            Incore::url('auth/login'),
            Incore::url('auth/logout'),
        ];

        foreach ($excepts as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->is($except)) {
                return true;
            }
        }

        return false;
    }
}
