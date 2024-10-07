<?php

namespace App\Models\Apps\Pos;

// use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MComponentData extends Model
{ 
	public static function getMenu($req, $outlet) {
        // $dateNA = sysDateNA($req->user()['user_branchaktif']);
        $dateNA = Carbon::now();
        // $dateNA = $req->backdate;
        $query = DB::table('ms_posmenu')
        ->where('rec_status', 1)
        // ->where('posmenu_branch', $req->user()['user_branchaktif']);
        ->where('posmenu_branch', 9);
        // ->limit(25);
        if(!empty($req->outlet)) {
            $query->where("posmenu_outlet", $req->outlet);

        } else {
            $query->where("posmenu_outlet", $outlet);

        }
        $query->whereRaw('"'.$dateNA.'" BETWEEN  posmenu_startdate AND posmenu_enddate');
        $query->select(
            'posmenu_id', 'posmenu_code', 'posmenu_name', 'posmenu_posmenumain', 'posmenu_posmenugroup', 'posmenu_cost', 'posmenu_active', 'posmenu_type', 'posmenu_outlet','posmenu_picture',
        );
        if(!empty($req->menugroup)) {
            $query->where('posmenu_posmenugroup', $req->menugroup);
        }

        $query = $query->get();
        $query = json_decode(json_encode($query),1);

        foreach($query as $key=>$val){
            $query[$key]['quantity'] = 0;
            $query[$key]['price'] = self::getMenuPrice($val['posmenu_id'], $req, $dateNA);
            $query[$key]['tax'] = self::getMenuTax($val['posmenu_id'], $req, $dateNA);
            $query[$key]['service'] = self::getMenuService($val['posmenu_id'], $req, $dateNA);
            $query[$key]['serviceAmount'] = ($query[$key]['price'] * $query[$key]['service']) / 100;
            $query[$key]['taxAmount'] = (($query[$key]['price'] * $query[$key]['tax']) / 100) + ((($query[$key]['price'] * $query[$key]['tax']) / 100) * ($query[$key]['service'] / 100));
            $query[$key]['pricesell'] = $query[$key]['price'] + $query[$key]['taxAmount'] + $query[$key]['serviceAmount'];
            $query[$key]['priceNet'] = $query[$key]['price'] + $query[$key]['taxAmount'] + $query[$key]['serviceAmount'];
            $query[$key]['priceid'] = self::getMenuPriceID($val['posmenu_id'], $req, $dateNA);
        }

        return $query;

    }

    public static function getMenuPrice($id, $req, $dateNA) {
        $user = $req->user();
        // $branch = $user['user_branchaktif'];
        $dateNow = date('Y-m-d H:i:s');
        // $dateNA = sysDateNA($branch);
        return DB::table('ms_posmenuprice')
            ->where('rec_status',1)
            ->where('posmenuprice_posmenu',$id)
            ->where('ms_posmenuprice.posmenuprice_branch', 9)
            ->whereDate('posmenuprice_startdate','<=', $dateNA)
            ->orderBy('posmenuprice_startdate', 'desc')
            ->value(
                db::raw('ifnull(posmenuprice_price, 0)')
            );

    }

    private static function getMenuTax($id, $req, $dateNA){
        $user = $req->user();
        // $branch = $user['user_branchaktif'];
        $dateNow = date('Y-m-d H:i:s');
        // $dateNA = sysDateNA($branch);
        return DB::table('ms_posmenuprice')
            ->leftJoin('ms_taxservice', 'ms_taxservice.taxservice_id', 'ms_posmenuprice.posmenuprice_vat')
            ->where('ms_posmenuprice.rec_status',1)
            ->where('ms_posmenuprice.posmenuprice_branch', 9)
            ->where('ms_posmenuprice.posmenuprice_posmenu',$id)
            ->whereDate('ms_posmenuprice.posmenuprice_startdate','<=', $dateNA)
            ->orderBy('ms_posmenuprice.posmenuprice_startdate', 'desc')
            ->value(
                db::raw('ifnull(ms_taxservice.taxservice_value, 0)')
            );
    }

    private static function getMenuService($id, $req, $dateNA) {
        $user = $req->user();
        // $branch = $user['user_branchaktif'];
        $dateNow = date('Y-m-d H:i:s');
        // $dateNA = sysDateNA($branch);
        return DB::table('ms_posmenuprice')
            ->leftJoin('ms_taxservice', 'ms_taxservice.taxservice_id', 'ms_posmenuprice.posmenuprice_service')
            ->where('ms_posmenuprice.rec_status',1)
            ->where('ms_posmenuprice.posmenuprice_branch', 9)
            ->where('ms_posmenuprice.posmenuprice_posmenu',$id)
            ->whereDate('ms_posmenuprice.posmenuprice_startdate','<=', $dateNA)
            ->orderBy('ms_posmenuprice.posmenuprice_startdate', 'desc')
            ->value(
                db::raw('ifnull(ms_taxservice.taxservice_value, 0)')
            );
    }

    public static function getMenuPriceID($id, $req, $dateNA) {
        $user = $req->user();
        // $branch = $user['user_branchaktif'];
        $dateNow = date('Y-m-d H:i:s');
        // $dateNA = sysDateNA($branch);
        return DB::table('ms_posmenuprice')
            ->where('rec_status',1)
            ->where('posmenuprice_posmenu',$id)
            ->where('ms_posmenuprice.posmenuprice_branch', 9)
            ->whereDate('posmenuprice_startdate','<=', $dateNA)
            ->orderBy('posmenuprice_startdate', 'desc')
            ->value(
                db::raw('posmenuprice_id')
            );

    }

    public static function createOrdersTemp($req) {

        $maindata = [
            'rec_status'    => 1,
            'rec_userstamp' => strtoupper($req->freeguest),
            'rec_timestamp' => DB::raw('NOW()'),
            'pos_ordertemp_branch'    => 9,
            'pos_ordertemp_outlet' => $req->outlet,
            'pos_ordertemp_date'      => date('Y-m-d'),
            'pos_ordertemp_foguest'   => $req->guestid,
            'pos_ordertemp_freeguest' => strtoupper($req->freeguest),
            'pos_ordertemp_pax'       => $req->pax,
            'pos_ordertemp_foroom'    => $req->room,
            'pos_ordertemp_status'    => 0,
        ];

        $callbackid = DB::table('tr_pos_order_temp_h')->insertGetId($maindata);

        foreach ($req['listOrders'] as $key => $value) {

            $listdata = [
                'rec_userstamp' => strtoupper($req->freeguest),
                'rec_timestamp' => DB::raw('NOW()'),
                'pos_ordertemp_id'        => $callbackid,
                'pos_ordertemp_menuid'   => $value['menuid'],
                'pos_ordertemp_qty'       => $value['quantity'],
            ];

            DB::table('tr_pos_order_temp_d')->insert($listdata);
        }
    }
}