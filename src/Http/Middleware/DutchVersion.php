<?php

namespace Spdotdev\SplotnikovDev\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spdotdev\SplotnikovDev\Dutch;
use Symfony\Component\HttpFoundation\Response;

/** Serves the Dutch version of a paired page: the same page, switched to Dutch on the server. */
final class DutchVersion
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $enPath = Dutch::enPath($request->path() === 'nl' ? '/nl' : '/'.$request->path());
        if ($response->getStatusCode() !== 200 || ! Dutch::paired($enPath)) {
            return $response->getStatusCode() === 200 ? abort(404) : $response;
        }
        $response->setContent(Dutch::transform((string) $response->getContent(), $enPath));

        return $response;
    }
}
