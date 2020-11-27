<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Lcobucci\JWT\Parser;

class isTokenExpired
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
        // $token = explode(" ", $request->header())[1];
        // $token = (new Parser)->parse($token);

        // $user = DB::table('users as u')
        //     ->join('oauth_access_tokens as oat', 'u.id', '=', 'oat.user_id')
        //     ->where('oat.id', $token)
        //     ->get();
        return response()->json($request->header(), 401);
    }
}
