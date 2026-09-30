<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdvertiseClientHints
{
    /**
     * Client Hints that improve device detection on subsequent requests.
     *
     * @var list<string>
     */
    private const CLIENT_HINTS = [
        'Sec-CH-UA',
        'Sec-CH-UA-Mobile',
        'Sec-CH-UA-Platform',
        'Sec-CH-UA-Platform-Version',
        'Sec-CH-UA-Model',
        'Sec-CH-UA-Form-Factors',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set(
            'Accept-CH',
            implode(', ', self::CLIENT_HINTS),
        );

        return $response;
    }
}
