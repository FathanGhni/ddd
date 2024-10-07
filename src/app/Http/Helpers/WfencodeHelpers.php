<?php 
if (! function_exists('genCode')) {
    function genCode($panjang = 55, $karakter=null)
    {
    	if(empty($karakter)){
    		$karakter = env('EXTERNAL_TOKEN_SECRET_KEY');
    	}
    	else{
    		$karakter .= env('EXTERNAL_TOKEN_SECRET_KEY');
    	}
        $panjangKata = strlen($karakter);
        $kode = '';
        for ($i = 0; $i < $panjang; $i++) {
            $kode .= $karakter[rand(0, $panjangKata - 1)];
        }
        return $kode;
    }
}
if (!function_exists("is_valid_domain_name")) {
  function is_valid_domain_name($domain_name) {
      return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name) //valid chars check
              && preg_match("/^.{1,253}$/", $domain_name) //overall length check
              && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name)   ); //length of each label
  }
}
if (! function_exists('getkode')) {
    function getkode()
    {
        $panjang = 55;
        $karakter       = 'kodingin.com4543534-039849kldsam][].';
        $panjangKata = strlen($karakter);
        $kode = '';
        for ($i = 0; $i < $panjang; $i++) {
            $kode .= $karakter[rand(0, $panjangKata - 1)];
        }
        return $kode;
    }
}
if (! function_exists('jangkrik')) {
    function jangkrik($kata) {
	    $panjang = strlen($kata);
	    if ($panjang <10)
	    {
	        $lft = '0'.$panjang;
	    }
	    else {
	        $lft = $panjang;
	    }    
	    $acak ='aDg%j'.$lft.'kl'.$lft.'Uk'.$lft.'ns'.$lft.'=7'.$lft.'8^7b'.$lft.'mlnHn2'.$lft.'8^7b'.$lft.'mlnHn2'.$lft.'';
	    $acakuse = substr($acak,($panjang+2),(strlen($acak)-($panjang+2)));    
	    $hasil ='';
	    for ($i=0;$i<$panjang;$i++)
	    {
	        $input = substr($kata,$i,1);
	        $medium = ord($input);
	        $output = chr($medium+3);
	        $hasil .= $output;
	    }
	    return $lft.$hasil.$acakuse;
	    
	}
}
if (! function_exists('kinjeng')) {
	function kinjeng($kata2) {
	    $panjang = substr($kata2,0,2);
	    $hasil ='';
	    for ($i=0;$i<$panjang;$i++)
	    {
	        $input = substr($kata2,$i+2,1);
	        $medium = ord($input);
	        $output = chr($medium-3);
	        $hasil .= $output;
	    }
	    return $hasil;    
	}
}