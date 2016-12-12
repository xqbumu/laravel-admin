<?php

namespace Intendant\{$stub_intendant_zone_upper}\Middleware;

use Intendant\{$stub_intendant_zone_upper}\Facades\Admin;
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
        if (Admin::user()) {
            $log = [
                'user_id' => Admin::user()->id,
                'path'    => $request->path(),
                'method'  => $request->method(),
                'ip'      => $request->getClientIp(),
                'input'   => json_encode($request->input()),
            ];

            call_user_func(array(Admin::configs('database.operation_log_model'), 'create'), $log);
        }

        return $next($request);
    }
}
