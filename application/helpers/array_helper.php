<?php

function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
    $sort_col = array();
    foreach ($arr as $key=> $row) {
        $sort_col[$key] = $row[$col];
    }

    array_multisort($sort_col, $dir, $arr);
}
function get_currency_symbol($abbreviation){
    static $symbol;

    if(!$symbol)
    {
        $symbol = get_array_currency_symbol();
    }
    
    if(isset( $symbol[$abbreviation]))
    {
        return $symbol[$abbreviation];
    }

    return "";
}

function get_array_currency_symbol(){
    static $symbol;

    if(!$symbol)
    {
        $symbol=array(
			"USD"=>"$",
			"EUR"=>"€",
			"MXN"=>"$",
			"JPY"=>"¥",
			"BOB"=>"Bs",
			"VEF"=>"VEF",
			"ANG"=>"NAƒ",
			"CLP"=>"$",
			"NIO"=>"C$",
			"COP"=>"$",
			"CAD"=>"C$",
			"SVC"=>"₡",
			"GTQ"=>"Q",
			"HNL"=>"L",
			"PEN"=>"S/.",
			"CRC"=>"₡",
			"PAB"=>"B",
			"PYG"=>"₲",
			"BRL"=>"R$",
			"DOP"=>"RD$",
			"ARS"=>"$",
			"UYU"=>"UYU"
		);

    }

    return $symbol;
}

