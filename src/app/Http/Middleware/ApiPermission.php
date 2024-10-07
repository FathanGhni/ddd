<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\MenuModel;
use App\User;
use DB;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Config;

class ApiPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $apiCode)
    {
        // Init 
        $version = config('version.vue');
        $request['api_access'] = ['code'=> $apiCode, 'is_allowed'=> true, 'title'=> null/*, 'btn_access'=> []*/];
        $usr = $request->user();
        $sys = DB::table('sy_update')->first();
        $system = (empty($sys->version)?'0.0.1':$sys->version);

        Config::set('app.debug', false);
        if(!empty($usr)){
            if(!empty($usr['user_developer'])){
                Config::set('app.debug', true);
            }
        }
        if(!empty($usr['id'])){
            DB::table('sy_user')->where(['id'=>$usr['id']])->update([
                'user_lastactived'=>date('Y-m-d H:i:s')
            ]);
            $cu = DB::table('sy_user')
            ->where([
                'id'=>$usr['id'],
            ])->first();
            if(!empty($cu->id)){
                if(empty($cu->rec_status)){
                    $msg = 'Account is blocked';
                }
                if(empty($cu->user_status)){
                    $msg = 'Account not actived';
                }
                if(!empty($msg)){
                    return response()->json([
                        'data'=> null,
                        'status'=> 0,
                        'message'=> $msg
                    ], 401);
                }
            }
            if(!empty($usr['custom']->company_id)){
                $cp = DB::table('sy_company')->where([
                    'company_id'=>$usr['custom']->company_id
                ])->first();
                $compName = (empty($cp->company_nama)?null:$cp->company_nama);
            }
        }

        if($apiCode){
            $check = MenuModel::checkAPIPermission($request->user(), $apiCode);

            if($check['is_allowed']){ // Allow access if has permission
                $request['api_access'] = $check;
                if(!empty($request->output)){
                    return $next($request);
                }
                if(!empty($cu->id)){

                }
                $response = $next($request);
                $res = json_decode($response->getContent(), true);
                $res['version']=(empty($usr['user_version'])?null:$usr['user_version']);
                $res['system']=$system;
                $res['branch']=(empty($usr['custom']->branch_id)?[]:[
                    'branchCode'=>$usr['custom']->branch_code,
                    'branchCompany'=>(empty($compName)?null:$compName),
                    'branchName'=>$usr['custom']->branch_name,
                ]);
                $res['systemDate']=(empty($usr['custom']->branch_sysdate)?null:$usr['custom']->branch_sysdate);
                $res = json_encode($res, true);
                $response->setContent($res);
                return $response;

            }else{
                return response()->json([
                    'data'=> null,
                    'status'=> 0,
                    'message'=> 'You dont have access for this action'
                ], 401);
            }
        }else{
            if(!empty($request->output)){
                return $next($request);
            }
            $response = $next($request);
            $res = json_decode($response->getContent(), true);
            $res['version']=(empty($usr['user_version'])?null:$usr['user_version']);
            $res['system']=$system;
            $res['branch']=(empty($usr['custom']->branch_id)?[]:[
                'branchCode'=>$usr['custom']->branch_code,
                'branchCompany'=>(empty($compName)?null:$compName),
                'branchName'=>$usr['custom']->branch_name,
            ]);
            $res['systemDate']=(empty($usr['custom']->branch_sysdate)?null:$usr['custom']->branch_sysdate);
            $res = json_encode($res, true);
            $response->setContent($res);
            return $response;
        }
    }
}
