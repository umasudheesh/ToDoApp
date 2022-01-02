<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class APIToken
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
        if($request->header('Authorization'))
        {
            $token = $request->header('Authorization');
            $user = User::where('api_token',$token)->first();

            if($user)
                return $next($request);
        }
        return response()->json([
            'message' => 'Not a valid API request.',
          ]);
    }
}
