<?php

namespace Webamooz\User\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StoreUserIp
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->ip != $request->ip()) {  //auth()->user()->ip != $request->ip() -> اگر آی پی تغییر کرد بیا بساز براش + لاگین باشه
            auth()->user()->ip = $request->ip();
            auth()->user()->save();
        }
        return $next($request);
    }
}
