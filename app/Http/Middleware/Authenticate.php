<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Exceptions\HttpResponseException;

class Authenticate extends Middleware
{
    protected function unauthenticated($request, array $guards)
    {
        $response = response()->json([
            'success' => false,
            'message' => 'Unauthorized',
            'data' => ['error' => 'Token not found or expired'],
        ], 401);

        throw new HttpResponseException($response);
    }
}
