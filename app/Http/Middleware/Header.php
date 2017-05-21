<?php

namespace App\Http\Middleware;

use Closure;

class Header
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $headers = [
           'Access-Control-Allow-Origin' => '*',
           'Access-Control-Allow-Methods'=> 'POST, GET, OPTIONS, PUT, DELETE',
           'Access-Control-Allow-Headers'=> 'X-Requested-With, Accept, Content-Type, Origin, Authorization'
        ];

        if($request->getMethod() == "OPTIONS") {
            // The client-side application can set only headers allowed in Access-Control-Allow-Headers
            return Response::make('OK', 200, $headers);
        }

        $response = $next($request);

        if ( $response->status() >= 200  && $response->status() <= 299)
            return $response;

        foreach($headers as $key => $value) {
            $response->headers->set($key, $value);
        }

        return $response;
    }
}