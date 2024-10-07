<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class DynamicDatabaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $domain = $request->header('Origin');
        $url = preg_replace("(^https?://)", "", $domain);
        $exp = explode('.', $url);
        $host='PRODUCTION';
        $host = strtoupper(trim(reset($exp)));
        $config = 'database.connections.mysql.';

        /*
            PERSIAPAN JIKA BEDA DATABASE atau SERVER
        */
        Config::set($config.'host', env($host.'_DB_HOST', env('DB_HOST')) );
        Config::set($config.'port', env($host.'_DB_PORT', env('DB_PORT')) );
        Config::set($config.'username', env($host.'_DB_USERNAME', env('DB_USERNAME')) );
        Config::set($config.'password', env($host.'_DB_PASSWORD', env('DB_PASSWORD')) );
        Config::set($config.'database', env($host.'_DB_DATABASE', env('DB_DATABASE')) );
        return $next($request);
    }
}
