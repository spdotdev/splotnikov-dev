<?php

namespace Spdotdev\SplotnikovDev\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        // With the database session driver and the database down, the form page would answer 500 and the visitor would
        // see nothing. The page itself needs no stored session to render, so it falls back to a per-request one (the
        // POST needs the database for the lead and says so on the page).
        if (config('session.driver') === 'database') {
            try {
                DB::connection(config('session.connection'))->getPdo();
            } catch (\Throwable) {
                config(['session.driver' => 'array']);
            }
        }

        return $next($request);
    }
}
