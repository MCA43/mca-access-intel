<?php

namespace Mca\AccessIntel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Mca\AccessIntel\Support\McaAccessIntelLocale;
use Symfony\Component\HttpFoundation\Response;

class SetMcaAccessIntelLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        McaAccessIntelLocale::apply();

        return $next($request);
    }
}
