<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Modapiexternal;
use App\User;
use Str;
use Illuminate\Contracts\Auth\Authenticatable;

class ExternalPermission
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
        $errorApiAuth=false;
        $errorHandler=null;
        $authHeader = null;
        $header = $request->header('Authorization');
        if (Str::startsWith($header, 'Bearer ')) {
            $authHeader =  Str::substr($header, 7);
        }
        if(empty($header)){
            $errorApiAuth=true;
        }
        if(empty($authHeader)){
            $errorApiAuth=true;
        }
        if(!empty($errorApiAuth)){
            return response()->json([
                'status'=> 0,
                'message'=> 'Not detect Bearer Token'
            ], 401);
        }
        $check = Modapiexternal::checkAPi($authHeader);
        if(!empty($check)){
            if(!empty($check->tokens_expired)){

            }
            if(!empty($check->tokens_domainIp)){

            }

            if(!empty($errorHandler)){
                return response()->json([
                    'status'=> 0,
                    'message'=> $errorHandler
                ], 401);
            }
            $request->dataApi = $check;
        }
        else{
            return response()->json([
                'status'=> 0,
                'message'=> 'You dont have permission Api Token'
            ], 401);
        }
    	return $next($request);
    }
}