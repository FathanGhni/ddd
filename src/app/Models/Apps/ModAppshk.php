<?php
namespace App\Models\Apps;

use Illuminate\Database\Eloquent\Model;
use DB;
class ModAppshk extends Model
{
	public static function getUserGuestByRoomno($req, $roomno) {

		$qbranch = db::table('sy_branch')
		->select('branch_id')
		->where('branch_code', strtoupper($req->branch))
		->where('rec_status', 1)
		->first();

		$query = db::table('tr_foreserve_room as fr')
		->select(
			'fr.foreserve_foguest', 
			'g.foguest_id as user_id', 
			// db::raw('IF(g.foguest_firstname IS NULL, concat(g.foguest_firstname,\' \', g.foguest_lastname), (g.foguest_preference) ) AS user_name'),
			// DB::raw('concat(g.foguest_preference,\' \', IF(g.foguest_lastname IS NULL,"",g.foguest_lastname)) as user_name '),
			// db::raw('g.foguest_preference'),
			db::raw('concat(g.foguest_firstname,\' \', g.foguest_lastname) as user_name'),
			'foreserve_branch as branch',
			'b.branch_code', 'b.branch_address', 'b.branch_logo', 'b.branch_brand', 'r.foroom_code'
		)
		->leftJoin('ms_foroom as r', 'r.foroom_id', 'fr.foreserve_roomno')
		->leftJoin('ms_foguest as g', 'g.foguest_id', 'fr.foreserve_foguest')
		->leftJoin('sy_branch as b', 'b.branch_id', 'fr.foreserve_branch')
		->where('fr.rec_status', 1)
		->whereNull('fr.foreserve_checkouttime')
		->where('fr.foreserve_branch', $qbranch->branch_id)
		->where('r.foroom_code', $roomno)
		->first();

		return $query;
	}
}