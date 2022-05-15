<?php


/*
doDateConvert($date);
Descrição: Converte a data para o formato BR
*/
function doDateConvert($date) {
	return ($date !== '' && $date !== NULL) ? date('d/m/Y', strtotime($date)) : '';
}


/*
doDateConvertMonthToString($month);
Descrição: Converte o mes numeral para seu nome por extenso
*/
function doDateConvertMonthToString($month) {
	$dateObj   = DateTime::createFromFormat('!m', $month);

	return utf8_encode(ucfirst(strftime( '%B', $dateObj -> getTimestamp())));
	
}



/*
doTimeConvert($time, $addTime = false, $format = false);
Descrição: converte uma string para formato time "10:00" as 10:00 am
*/
function doTimeConvert($time, $addTime = false, $format = false) {
	$format = ($format !== false) ? 'H:i:s' : 'H:i';
	$addHours = date($format, strtotime($addTime, strtotime($time)));
	$Hours = date($format, strtotime($time));

	return ($addTime !== false) ? $addHours : $Hours;
}

/*
isTimeValidation($time)
Descrição: Valida se o horário é existente no formato 24hrs
*/
function isTimeValidation($time) {

	if($time) {
		$start = new DateTime('00:00');
		$end = new DateTime('23:59');
		$now = new DateTime($time);
	}
	return ($time > 0 && ($start <= $now && $now <= $end)) ? true : false;
}



/*
doDateList($yearMin, $yearMax)
Descrição: lista a data de do ano atual até 100 anos atrás(ou de quando desejar)
*/
function doDateList($yearMin = false, $yearMax = false) {
	$yearMin = ($yearMin !== false) ? $yearMin : date("Y")-100;
	$yearMax = ($yearMax !== false) ? $yearMax : date("Y");
	
	$years = array();

	/*$years = array(
		2022 = array(
			1 => 31,
			2 => 31,
			3 => 31,
			4 => 31,
			5 => 31,
			6 => 31,
			7 => 31,
			8 => 31,
			9 => 31,
			10 => 31,
			11 => 31,
			12=> 31
		)


	);*/
	for($yearMin; $yearMin <= $yearMax; ++$yearMin) {
		
		for($month = 1; $month <= 12; ++$month) {
			if($month == 1 || $month == 3  || $month == 5  || $month == 7  || $month == 8  || $month == 10 || $month == 12)
				$years[$yearMin][$month] = 31;
			elseif($month == 2)
				if (($yearMin % 4 == 0) && ($yearMin % 100 != 0) || ($yearMin % 400 == 0)) {
					$years[$yearMin][$month] = 29;
				} else {
					$years[$yearMin][$month] = 28;
				}
			else 
				$years[$yearMin][$month] = 30;
		}


	}
	return ($years);
}


/*
getDaysInMonth($n)
Descrição: verifica a quantidade de dias no mês x do ano y
*/
function getDaysInMonth($n, $year) {
	$months = doDateList($year);
	return (array_key_exists($year, $months) && array_key_exists($n, $months[$year])) ? $months[$year][$n] : false;
}

?>