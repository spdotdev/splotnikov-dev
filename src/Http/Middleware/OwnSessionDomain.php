<?php

namespace Spdotdev\SplotnikovDev\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The host application shares one session cookie domain (.scuttle.dev) for its tools. A browser rejects a cookie whose
 * Domain is not a suffix of the page's host, so on splotnikov.dev the session never stuck, the CSRF token never matched and
 * every contact form submission ended in "Page Expired" (found 2026-10-30). The contact routes use a host-only cookie.
 */
final class OwnSessionDomain
{
    public function handle(Request $request, Closure $next): Response
    {
        config(['session.domain' => null]);

        return $next($request);
    }
}
