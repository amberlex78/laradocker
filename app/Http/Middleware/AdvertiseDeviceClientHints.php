<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdvertiseDeviceClientHints
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('Accept-CH', 'Sec-CH-UA-Model, Sec-CH-UA-Form-Factors');
        $response->headers->set('Permissions-Policy', 'ch-ua-model=(self), ch-ua-form-factors=(self)');

        return $response;
    }
}
