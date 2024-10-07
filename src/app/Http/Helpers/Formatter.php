<?php

if (! function_exists('sysDateNA')) {
    function sysDateNA($branch){
 		$brc = DB::table('sy_branch')->select([
            'branch_id','branch_sysdate'
        ])->where(['branch_id'=>$branch])->first();
        if(!empty($brc)){
            return (empty($brc->branch_sysdate)?null:$brc->branch_sysdate);
        }
        return null;
    }
}
if (! function_exists('rupiahPdfEx')) {
    function rupiahPdfEx($nominal=0, $tipeFile="pdf", $decimal=0){
		$nominal = floatval($nominal);
 		$numb = $nominal;
 		if(!empty($tipeFile)){
	 		if($tipeFile=='pdf'){
	 			$numb = number_format($nominal, $decimal, ".",',');
	 		}
	 		else if($tipeFile=='excel'){
	 			$numb = $nominal;
	 		}
 		}
 		else{
	 		$numb = number_format($nominal, $decimal, ".",',');
 		}
        return $numb;
    }
}
if (! function_exists('dateBackOffice')) {
    function dateBackOffice($branch){
 		$brc = DB::table('ms_invsetup')->select([
            'invetup_id','invsetup_date'
        ])->where([
        	'invsetup_branch'=>$branch,
        	'rec_status'=>1,
        	'invsetup_code'=>'DateBackOffice',
        ])->first();
        if(!empty($brc->invetup_id)){
            return (empty($brc->invsetup_date)?null:$brc->invsetup_date);
        }
        return null;
    }
}
if (! function_exists('foSetup')) {
    function foSetup($code=null, $branch=null){
        $setUp = DB::table('ms_fosetup') 
        ->where([
            'rec_status'=>1,
            'fosetup_code'=>$code,
            'fosetup_branch'=>$branch,
        ])->first();
        if(empty($setUp->fosetup_id)){
	        $setUps = DB::table('ms_fosetup')
	        ->whereRaw('fosetup_branch IS NULL')
	        ->where([
            	'rec_status'=>1,
	            'fosetup_code'=>$code,
	        ])->first();
        	return (empty($setUps)?null:$setUps);
        }
        return (empty($setUp)?null:$setUp);
    }
}
if (! function_exists('invSetup')) {
    function invSetup($code=null, $branch=null, $where=null){
        $s1 = DB::table('ms_invsetup') 
        ->whereRaw('invsetup_code="'.$code.'" ')
        ->where([
            'rec_status'=>1,
            'invsetup_branch'=>$branch,
        ]);
        if(!empty($where)){
        	$s1->where($where);
        }
        $setUp = $s1->first();
        if(empty($setUp->invetup_id)){
	        $s2 = DB::table('ms_invsetup')
	        ->whereRaw('invsetup_branch IS NULL')
        	->whereRaw('invsetup_code="'.$code.'"')
	        ->where([
            	'rec_status'=>1,
	        ]);
	        if(!empty($where)){
	        	$s2->where($where);
	        }
	        $setUps = $s2->first();
        	return (empty($setUps)?null:$setUps);
        }
        return (empty($setUp)?null:$setUp);
    }
}
if (! function_exists('glSetup')) {
    function glSetup($code=null, $branch=null, $where=null){
        $s1 = DB::table('ms_glsetup') 
        ->whereRaw('glsetup_code="'.$code.'" ')
        ->where([
            'rec_status'=>1,
            'glsetup_branch'=>$branch,
        ]);
        if(!empty($where)){
        	$s1->where($where);
        }
        $setUp = $s1->first();
        if(empty($setUp->glsetup_id)){
	        $s2 = DB::table('ms_glsetup')
	        ->whereRaw('glsetup_branch IS NULL')
        	->whereRaw('glsetup_code="'.$code.'"')
	        ->where([
            	'rec_status'=>1,
	        ]);
	        if(!empty($where)){
	        	$s2->where($where);
	        }
	        $setUps = $s2->first();
        	return (empty($setUps)?null:$setUps);
        }
        return (empty($setUp)?null:$setUp);
    }
}
if (! function_exists('defaultFromGlList')) {
    function defaultFromGlList($branch){
		$qtglClose = glSetup('CLOSEMONTH', $branch);
		$tglClose = (empty($qtglClose->glsetup_date)?null:$qtglClose->glsetup_date);
		$fromDate = $tglClose ? date('Y-m-d', strtotime($tglClose.' +1 day')) : null;

		return $fromDate;
	}
}

if (! function_exists('apSetup')) {
    function apSetup($code=null, $branch=null, $where=null){
        $s1 = DB::table('ms_apsetup') 
        ->whereRaw('apsetup_code="'.$code.'" ')
        ->where([
            'rec_status'=>1,
            'apsetup_branch'=>$branch,
        ]);
        if(!empty($where)){
        	$s1->where($where);
        }
        $setUp = $s1->first();
        if(empty($setUp->apsetup_code)){
	        $s2 = DB::table('ms_apsetup')
	        ->whereRaw('apsetup_branch IS NULL')
        	->whereRaw('apsetup_code="'.$code.'"')
	        ->where([
            	'rec_status'=>1,
	        ]);
	        if(!empty($where)){
	        	$s2->where($where);
	        }
	        $setUps = $s2->first();
        	return (empty($setUps)?null:$setUps);
        }
        return (empty($setUp)?null:$setUp);
    }
}
if (! function_exists('arSetup')) {
    function arSetup($code=null, $branch=null, $where=null){
        $s1 = DB::table('ms_arsetup') 
        ->whereRaw('arsetup_code="'.$code.'" ')
        ->where([
            'rec_status'=>1,
            'arsetup_branch'=>$branch,
        ]);
        if(!empty($where)){
        	$s1->where($where);
        }
        $setUp = $s1->first();
        if(empty($setUp->arsetup_code)){
	        $s2 = DB::table('ms_arsetup')
	        ->whereRaw('arsetup_branch IS NULL')
        	->whereRaw('arsetup_code="'.$code.'"')
	        ->where([
            	'rec_status'=>1,
	        ]);
	        if(!empty($where)){
	        	$s2->where($where);
	        }
	        $setUps = $s2->first();
        	return (empty($setUps)?null:$setUps);
        }
        return (empty($setUp)?null:$setUp);
    }
}
if (! function_exists('getTax')) {
    function getTax($code=null, $branch=null, $typeTax=1){
        $setUp = DB::table('ms_taxservice')
        ->select(['taxservice_name', 'taxservice_value', 'taxservice_coa'])
        ->where([
            'rec_status'=>1,
            'taxservice_code'=>$code,
            'taxservice_type'=>$typeTax,
            'taxservice_branch'=>$branch,
        ])->first();
        return (empty($setUp)?null:$setUp);
    }
}
if (! function_exists('hideCreditCard')) {
    function hideCreditCard($payId=null, $refNo=null){
    	if(empty($payId)) return null;
    	$paykateg = DB::table('ms_payarticlecategory')->where([
            'rec_status'=>1,
            'payarticlecategory_id'=>$payId,
        ])->first();
        if(!empty($paykateg->payarticlecategory_enkriprefnumber)){
            $jml = (int)$paykateg->payarticlecategory_enkriprefnumber;
            if(strlen($refNo)>=$jml){
                $ref = substr_replace($refNo, str_repeat("x", strlen($refNo)-5), 1, strlen($refNo)-5);
                return $ref;
            }
        }
        return null;
    }
}
if (! function_exists('loopDate')) {
    function loopDate($start=null, $end=null, $arrange=1, $modif="+1 day", $format="Y-m-d"){
		$dateRange=[];
        $begin = new \DateTime($start);
        $end = new \DateTime($end);
        if($arrange==1){ // <=
	        for($i = $begin; $i <= $end; $i->modify($modif)){
	            $dateRange[] = $i->format($format);
	        }
        }
        else{ // <
	        for($i = $begin; $i < $end; $i->modify($modif)){
	            $dateRange[] = $i->format($format);
	        }
        }
        return $dateRange;
    }
}
if (! function_exists('loopDateMth')) {
    function loopDateMth($first=null, $last=null, $step ="+1 day", $format="Y-m-d"){
		 $dates = array();
        $current = strtotime($first);
        $last = strtotime($last);

        while( $current <= $last ) {    
            $dates[] = date($format, $current);
            $current = strtotime($step, $current);
        }
        return $dates;
    }
}
if (! function_exists('date_indo')) {
    function date_indo($tanggal){
		// $newDate = date_format($tanggal, 'Y-m-d');

		$bulan = array (
			1 =>   'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember'
		);
		$pecahkan = explode('-', $tanggal);
	 
		return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
	}
}
if (! function_exists('status_roomadmin')) {
    function status_roomadmin(){
    	$data = [
    		'Clean Checked',
    		'Clean Uncheck',
    		'Vacant Dirty',
    		'DnD',
    		'Out off Order',
    		'Off Market',
    		'Out off Service'
    	];
    	return $data;
    }
}
if (! function_exists('listStatus')) {
	function listStatus(){
    	$data = [
    		['id'=>1, 'name'=>'Vacant Clean Checked', 'code'=>'VCC'],
			['id'=>2, 'name'=>'Vacant Clean Unchecked', 'code'=>'VCU'],
			['id'=>3, 'name'=>'Vacant Dirty', 'code'=>'VD'],
			// ['id'=>4, 'name'=>'Dont Disturb', 'code'=>'DnD'],
			['id'=>5, 'name'=>'Out Off Order', 'code'=>'OOO'],
			['id'=>6, 'name'=>'Off Market', 'code'=>'OM'],
			['id'=>7, 'name'=>'Out Off Service', 'code'=>'OOS'],
			['id'=>8, 'name'=>'Check Out', 'code'=>'CO'],
			['id'=>9, 'name'=>'Occupied Dirty', 'code'=>'OD'],
			['id'=>10, 'name'=>'Occupied Cleaned', 'code'=>'OC'],
			['id'=>11, 'name'=>'Expected Daparture', 'code'=>'ED'],
			['id'=>99, 'name'=>'Dont Disturb', 'code'=>'DnD'],
    	];
    	return $data;
	}
}
if (! function_exists('hkroomstatus')) {
    function hkroomstatus($removeId=array(), $id=null){
    	$data = listStatus();
    	if(!empty($removeId)){
    		$newData=[];
    		foreach($data as $k => $v){
    			if(!in_array($v['id'], $removeId)){
    				$newData[] = $v;
    			}
    		}
    		return $newData;
    	}
    	if(!empty($id)){
    		foreach($data as $ked => $vl){
    			if((int)$vl['id']==(int)$id){
    				$out = $data[$ked];
    			}
    		}
    		if(!empty($out)){
    			return $out;
    		}
    		else{
    			return null;;
    		}
    	}
    	return $data;
    }
}
if (! function_exists('billingStatus')) {
    function billingStatus($fobill_status=null){ //fobill_status
    	$bill = [
    		null,
    		'Rsv Close Deposit',
    		'Deposit cancel',
    		null, //3
    		'Transfer Depo - Stay Bill',
    		'Room Share',
            'Close Bill',
    	];
    	return (empty($bill[$fobill_status])?null:$bill[$fobill_status]);
    }
}
if (! function_exists('typeBill')) {
    function typeBill($id=null){
    	$data = [
    		['id'=>1, 'name'=>'Stay Bill'],
    		['id'=>2, 'name'=>'Non Stay'],
    		['id'=>3, 'name'=>'Master Bill'],
    		['id'=>4, 'name'=>'Deposit'],
    	];
    	if(!empty($id)){
	    	foreach($data as $ked => $vl){
	    		if($vl['id']==$id){
	    			return $vl;
	    		}
	    	}
    	}
    	return $data;
    }
}
if (! function_exists('status_roomadmin_small')) {
    function status_roomadmin_small(){
    	$data = [
    		'VCC',
    		'VCU',
    		'VD',
    		'DnD',
    		'OOO',
    		'OOM',
    		'OOF'
    	];
    	return $data;
    }
}
if (!function_exists('paymentType')) {
    function paymentType($id=null){
    	$data = [
    		['id'=>1, 'name'=>'Sales'],
    		['id'=>2, 'name'=>'Payment'],
    	];
    	if(!empty($id)){
    		foreach ($data as $key => $val) {
    			if($val['id']==$id){
    				return $val;
    			}
    		}
    	}
    	return $data;
    }
}

if(!function_exists('konversi')){
	function konversi($x){
		
		$x = abs($x);
		$angka = array ("","satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		$temp = "";
		
		if($x < 12){
			$temp = " ".$angka[$x];
		}else if($x<20){
			$temp = konversi($x - 10)." belas";
		}else if ($x<100){
			$temp = konversi($x/10)." puluh". konversi($x%10);
		}else if($x<200){
			$temp = " seratus".konversi($x-100);
		}else if($x<1000){
			$temp = konversi($x/100)." ratus".konversi($x%100);   
		}else if($x<2000){
			$temp = " seribu".konversi($x-1000);
		}else if($x<1000000){
			$temp = konversi($x/1000)." ribu".konversi($x%1000);   
		}else if($x<1000000000){
			$temp = konversi($x/1000000)." juta".konversi($x%1000000);
		}else if($x<1000000000000){
			$temp = konversi($x/1000000000)." milyar".konversi($x%1000000000);
		}
		
		return $temp;
	}
}
	
if(!function_exists('tkoma')){
	function tkoma($x){
		$str = stristr($x,".");
		$ex = explode('.',$x);
		$a = 0;
		if(($ex[1]/10) >= 1){
			$a = abs($ex[1]);
		}
		$string = array("nol", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan","sepuluh", "sebelas");
		$temp = "";
	 
		$a2 = $ex[1]/10;
		$pjg = strlen($str);
		$i =1;
		
		
		if($a>=1 && $a< 12){   
			$temp .= " ".$string[$a];
		}else if($a>12 && $a < 20){   
			$temp .= konversi($a - 10)." belas";
		}else if ($a>20 && $a<100){   
			$temp .= konversi($a / 10)." puluh". konversi($a % 10);
		}else{
			if($a2<1){
		
				while ($i<$pjg){     
					$char = substr($str,$i,1);     
					$i++;
					$temp .= " ".$string[$char];
				}
		 	}
		}  
		return $temp;
	}
}
if(!function_exists('printPdf')){
	function printPdf($temp, $setup=null){
		$setups = [
            'tempDir' => storage_path('tempdir'),
            'mode' => 'utf-8', 
            'format' => 'A4-P', 
            'orientation' => 'P',
            'displayDefaultOrientation' => true,
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 5,
            'margin_bottom' => 5,
        ];
        
		if(!empty($setup)){

		}
		$mpdf = new \Mpdf\Mpdf($setups);
        $mpdf->AddPage();

        $html = view($temp['template'], $temp)->render();
        $mpdf->WriteHTML($html);

	    $output = $mpdf->Output('test.pdf','D');
	    $pdfBase64 = base64_encode($output);
	    // return 'data:application/pdf;base64,' . $pdfBase64;
	    return $output;
	}
}

if(!function_exists('terbilang')){
	function terbilang($x){
		$val = floatval($x);
		$poin = false;
		if($val<0){
			$hasil = "minus ".trim(konversi($val));
		}else{
			if(str_contains(strval($val), '.')){
				$poin = trim(tkoma(strval($val)));
			}
			$hasil = trim(konversi($val));
		}
		
		if($poin){
			$hasil = $hasil." koma ".$poin;
		}else{
			$hasil = $hasil;
		}
		return $hasil.' rupiah';  
	}
}
if(!function_exists('cekAksesMenu')){
	function cekAksesMenu($groups, $menuId=null){
		if(empty($groups)) return false;
		if(!empty($groups)){
			$group = json_decode($groups, TRUE);

			$cek = DB::table('sy_truste AS a')
			->join('sy_menu AS b','a.truste_menu','=','b.menu_id')
			->groupBy('b.menu_id')
			->whereIn('a.truste_usergroup', $group)
			->where([
				'a.rec_status'=>1,
				'b.rec_status'=>1,
				'b.menu_status'=>1,
				'a.truste_set'=>1,
				'a.truste_menu'=>$menuId,
			])->count();
			if(!empty($cek)){
				return true;
			}
			return false;
		}
	}
}
if(!function_exists('readMore')){
	function readMore($input, $length, $ellipses = true, $strip_html = true) {
		//strip tags, if desired
		if ($strip_html) {
			$input = strip_tags($input);
		}//no need to trim, already shorter than trim length
		if (strlen($input) <= $length) {
			return $input;
		}
			//find last space within length
			$last_space = strrpos(substr($input, 0, $length), ' ');
			$trimmed_text = substr($input, 0, $length);

			//add ellipses (...)
		if ($ellipses) {
			$trimmed_text .= '...';
		}
		return $trimmed_text;
	}
}
if(!function_exists('readMores')){
	function readMores($input, $length, $format = 'pdf', $strip_html = true) {
		if($format!='pdf'){
			return $input;
		}
		//strip tags, if desired
		if ($strip_html) {
			$input = strip_tags($input);
		}//no need to trim, already shorter than trim length
		if (strlen($input) <= $length) {
			return $input;
		}
		$last_space = strrpos(substr($input, 0, $length), ' ');
		$trimmed_text = substr($input, 0, $length);

		$trimmed_text .= '...';
		return $trimmed_text;
	}
}