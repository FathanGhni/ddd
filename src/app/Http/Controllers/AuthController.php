<?php

namespace App\Http\Controllers;

use File;
use App\User;
use Validator;
use Carbon\Carbon;
use \Firebase\JWT\JWT;
use App\Jobs\MailerJob;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function submitVerifyEmail(Request $request) {
        $token = base64_decode($request->input('token', 'MDAw'));
        $validator = Validator::make($request->all(), [
			'password' => 'required',
		]);

        if($validator->fails()) {
			return response()->json([
				'data'=> null,
				'status'=> 0,
				'message'=> $validator->errors()->first()
			], 200);
		} else {
            $result = UserModel::submitVerifyEmail($token, $request->input('password'));
            return response()->json([
                'data'=> null,
				'status'=> $result[0] ? 0 : 1,
				'message'=> $result[0]
            ], 200);
        }
    }

    public function verifyEmail(Request $request) {
        $token = $request->input('token', 'MDAw');
        $detail = $request->input('detail', false);
        $result = UserModel::checkVerifyEmailToken(base64_decode($token));

        // $url = env('APP_WEB').'/system/system-user/profile?changeStatus='.(!$result ? 'success' : $result);
        $url = env('APP_WEB').'/change-email?token='.$token;
        if ($detail) {
            return response()->json([
                'data'=> $result[1],
				'status'=> $result[0] ? 0 : 1,
				'message'=> $result[0]
            ], 200);
        } else {
            return redirect()->away($url);
        }
    }

    public function changeEmail(Request $request) {
        $validator = Validator::make($request->all(), [
			'newEmail' => 'required',
		]);

        if($validator->fails()) {
			return response()->json([
				'data'=> null,
				'status'=> 0,
				'message'=> $validator->errors()->first()
			], 200);
		} else {
            $message = null;
            $userObj = $request->user();
            $result = UserModel::checkAvailableGenerateToken($userObj['user_id'], 'email', $request->input('newEmail'));

            if(is_string($result)) {
                return response()->json([
                    'data'=> null,
                    'status'=> 0,
                    'message'=> $result
                ], 200);
            } else {
                if(!empty($result)) {
                    $result = [
                        'name'=> $result->user_name,
                        'user_id'=> $result->user_id,
                        'phone'=> $result->user_phone,
                        'departments'=> $result->depts,
                        'user_group'=> $result->usergroups,
                        'last_active'=> $result->user_lastactived,
                        'email'=> $result->user_mail,
                        'email_temp'=> $result->user_mail_temp,
                        'mail_verification_expiredAt'=> $result->user_mail_verification_token_expiredAt,
                        'verification_token'=> $result->user_mail_verification_token,
                        'branches'=> $result->branches,
                    ];
                    
                    MailerJob::dispatch($result['email_temp'], 'Email Verification', ['token'=> base64_encode($result['verification_token'])], 'EmailVerification');
                }
                return response()->json([
                    'data'=> $result,
                    'status'=> 1,
                    'message'=> $message
                ], 200);
            }
        }
    }

    public function updatePassword(Request $request) {
        $validator = Validator::make($request->all(), [
			'password' => 'required|string|min:8',
            'token'=> 'required',
		]);

		if($validator->fails()) {
			return response()->json([
				'data'=> null,
				'status'=> 0,
				'message'=> $validator->errors()->first()
			], 200);
		}

        $userObj = UserModel::updatePassword($request->input('password'), base64_decode($request->input('token', 'MDAw')));

        return response()->json([
            'data'=> null,
            'status'=> $userObj ? 1 : 0,
            'message'=> $userObj ? null : 'User not found or token is invalid'
        ], 200);
    }

    public function sendForgotPassLink(Request $request) {
        $validator = Validator::make($request->all(), [
			'userid' => 'required|string',
		]);

		if($validator->fails()) {
			return response()->json([
				'data'=> null,
				'status'=> 0,
				'message'=> $validator->errors()->first()
			], 200);
		}

        $userObj = UserModel::getuserbyuserid($request->input('userid'));

        if(!$userObj){
            return response()->json([
                'data'=> null,
                'status'=> 0,
                'message'=> 'User ID not registered',
            ], 200);
        } else {
            MailerJob::dispatch($userObj->email, 'Forgot Password', ['token'=> base64_encode($userObj->reset_token)], 'ForgotPassword');
            // dispatch(new MailerJob($userObj->email, 'Email Verification', ['token'=> $userObj->reset_token]), 'ForgotPassword');
            return response()->json([
                'data'=> $userObj,
                'status'=> 1,
                'message'=> null,
            ], 200);
        }
    }

    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string'
        ]);


        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);


        $user->save();


        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }

    public function loginwf(Request $request){
        $request->validate([
            'apitoken' => 'required|string'
        ]);

        $publickey = File::get(storage_path('oauth-public.key'));
        $err = false;
        try{
            $decoded = JWT::decode($request->input('apitoken'), $publickey, array('RS256'));
        }catch(\Exception $e){
            $err = true;
        }


        if($err){
            return response()->json([
                'status'=> 'error',
                'message'=> 'Invalid token provided',
                'result'=> null
            ], 401);
        }else{

            $userObj = UserModel::getuserbyuseridnumber($decoded->sub);
            $request->merge([
                'user_id'=> $userObj->user_id,
                'password'=> kinjeng($userObj->user_sandi),
                'remember_me'=> TRUE
            ]);

            return self::login($request);
        }


    }


    public function login(Request $request)
    {
        $request->validate([
            'user_id' => 'required|string',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        $userid = $request->input('user_id');
        $password = $request->input('password');

        // ambil array value userid dan password
        $credentials = request(['user_id', 'password']);

        $counter = UserModel::countFailedLogin($userid, $password, false, $request->ip());

        if ($counter >= 5) {
            return response()->json([
                'status'=> 'error',
                'message'=> 'Too many failed login attempts. Please call the admin for support',
                'result'=> null
            ], 401);
        }
        // cek credential cocok gak dgn yg di database
        if (!Auth::attempt($credentials)) {
            UserModel::countFailedLogin($userid, $password, true, $request->ip());
            return response()->json([
                'status'=> 'error',
                'message'=> 'User ID or Password is incorrect',
                'result'=> null
            ], 401);
        }

        // Reset counter gagal login jika berhasil login
        UserModel::resetCounterFailedLogin($userid);
        // $request->user() returns an instance of the authenticated user..
        $user = $request->user();


        // https://laravel.com/docs/5.8/passport#managing-personal-access-tokens
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;


        if ($request->remember_me) {
            // kasih expired seminggu
            $token->expires_at = Carbon::now()->addWeeks(1);
        }


        // save token expired kalo gak remember me expirednya default sekitar 5 jam
        $token->save();


        return response()->json([
            'status'=> 'ok',
            'message'=> null,
            'result'=> [
                'access_token' => $tokenResult->accessToken,
                'default_pass'=> ($request->input('password') == '12345678'),
                'token_type' => 'Bearer',
                'user' => array(
                    'id'=>$user->user_id,
                    // 'email'=>$user->email,
                    'nama'=>$user->user_name,
                ),
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ]
        ]);
    }


    public function user(Request $request)
    {
        // jangan lupa kasih header authorization Bearer token
        return response()->json($request->user());
    }


    public function logout(Request $request)
    {
        // unauthorized token
        $request->user()->token()->revoke();


        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
