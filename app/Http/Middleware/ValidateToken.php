<?php

namespace App\Http\Middleware;

use App\OauthAccessToken;
use Carbon\Carbon;
use Closure;

class ValidateToken
{
    /**
     * Persist token base on rules, if not active within 1 day force delete to expire token on client side
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = explode(' ', $request->header('Authorization'));
        $token = end($token);
        $oauthToken = OauthAccessToken::find($token);

        if ($oauthToken) {
            //check if expire time exceeds 1 day then force delete this token to logout the user
            $expiry = Carbon::createFromTimestamp($oauthToken->expire_time);

            if($expiry->diffInHours(Carbon::now()) > 24) { //24 hours
                $oauthToken->delete();
            } else { //if not refresh expiry by 1 hour
                $oauthToken->expire_time = $expiry->subMinutes($expiry->diffInMinutes(Carbon::now()))->timestamp;
                $oauthToken->save();
            }
        }

        return $next($request);
    }
}
