<?php
namespace App\Http\Controllers\Apps\Hk;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Apps\ModAppshk;
use App\Models\Apps\Pos\MComponentData;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use \Firebase\JWT\JWT;
use File;
use Validator;
use App\Jobs\MailerJob;

class Hkapps extends Controller
{
	public static function userSignin(Request $request) {
		$request->validate([
            'roomno' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        $roomno = $request->input('roomno');

        $userguest = ModAppshk::getUserGuestByRoomno($request, $roomno);

        // ambil array value userid dan password
        // $credentials = request(['roomno']);

        // $counter = UserModel::countFailedLogin($userid, $password, false, $request->ip());

        // if ($counter >= 5) {
        //     return response()->json([
        //         'status'=> 'error',
        //         'message'=> 'Too many failed login attempts. Please call the admin for support',
        //         'result'=> null
        //     ], 401);
        // }
        // // cek credential cocok gak dgn yg di database
        // if (!Auth::attempt($credentials)) {
        //     UserModel::countFailedLogin($userid, $password, true, $request->ip());
        //     return response()->json([
        //         'status'=> 'error',
        //         'message'=> 'User ID or Password is incorrect',
        //         'result'=> null
        //     ], 401);
        // }

        // Reset counter gagal login jika berhasil login
        // UserModel::resetCounterFailedLogin($userid);
        // $user = $request->user();


        // https://laravel.com/docs/5.8/passport#managing-personal-access-tokens
        // $tokenResult = $userguest->createToken('Personal Access Token');
        // $token = $tokenResult->token;


        // if ($request->remember_me) {
        //     // kasih expired seminggu
        //     $token->expires_at = Carbon::now()->addWeeks(1);
        // }


        // // save token expired kalo gak remember me expirednya default sekitar 5 jam
        // $token->save();


        return response()->json([
            'status'=> 'ok',
            'message'=> null,
            // 'user'=> Str::random()
            'result'=> [
                'access_token' => Str::random(),
                'default_pass'=> ($request->input('password') == '12345678'),
                'token_type' => 'Bearer',
                'user' => array(
                    'id'=> empty($userguest->user_id)?null:$userguest->user_id,
                    // 'email'=>$user->email,
                    'nama'=>empty($userguest->user_name)?null:$userguest->user_name,
                    'room'=>empty($userguest->foroom_code)?null:$userguest->foroom_code,
                    'branch'=>empty($userguest->branch)?null:$userguest->branch,
                    'branch_logo'=>empty($userguest->branch_logo)?null:$userguest->branch_logo,
                    'branch_name'=>empty($userguest->branch_brand)?null:$userguest->branch_brand,
                    'branch_address'=>empty($userguest->branch_address)?null:$userguest->branch_address,
                ),
                // 'expires_at' => Carbon::parse(
                //     $tokenResult->token->expires_at
                // )->toDateTimeString()
            ]
        ]);
	}

	public static function getApps(Request $request) {
		$config = ModAppshk::config($request);
	}

	public static function getComponentPOS(Request $req) {
		$output['menus'] = MComponentData::getMenu($req, 11);
	
		return response()->json([
			'result'=> $output,
			'status'=> true,
			'message'=> null
		], 200);
	}

	public static function orderPOSTemp(Request $req) {
		$query = MComponentData::createOrdersTemp($req);

		return response()->json([
			'result'=> $query,
			'status'=> true,
			'message'=> null
		], 200);
	}
}