<?php
ini_set('memory_limit', '900000M');
ini_set("pcre.backtrack_limit", "100000000");
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
// header('Access-Control-Allow-Headers: Content-Type, X-Auth-Token, Origin, Authorization');


use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

if(request()->method() != 'OPTIONS' ){
  \Log::info(request());
}
Route::get('PhpInfo', function () {
    return phpinfo();
});


Route::group([
    'prefix' => 'auth'
], function () {
    Route::group(['prefix' => 'apps'], function() {
		Route::middleware('checkPermission:')->post('signin', 'Apps\Hk\HkApps@userSignin');
	});
});

Route::group([ 'prefix' => 'apps' ], function () {
	Route::group([ 'prefix' => 'hk' ], function () {
		Route::post('config', 'Apps\Hk\Hkapps@getApps');
	});
});