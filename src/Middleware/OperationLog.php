<?php

namespace Encore\Incore\Middleware;

use Encore\Incore\Facades\Docore
use Illuminate\Http\Request;

class OperationLog
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        if (Docore::user()) {
            $log = [
                'user_id' => Docore::user()->id,
                'path'    => $request->path(),
                'method'  => $request->method(),
                'ip'      => $request->getClientIp(),
                'input'   => json_encode($request->input()),
            ];

            \Encore\Incore\Auth\Database\OperationLog::create($log);
        }

        return $next($request);
    }
}
