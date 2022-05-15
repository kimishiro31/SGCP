<?php

	/*****************************
	**
	**	FUNÇÕES GET
	**
	******************************/


/* getCashierData($cashier_ID, campo1, campo2...);
// captura as colunas da tabela Account e joga num array
	MODO DE USO
	$account = getCashierData(account_ID, 'password', 'email');
	echo $account['email']; ele vai retorna com o email
*/
function getCashierData($cashier_ID) {
	$data = array();
	$cashier_ID = sanitize($cashier_ID);
	
	$func_num_args = func_num_args();
	$func_get_args = func_get_args();
	
	if ($func_num_args > 1)  {
		unset($func_get_args[0]);
		
		$fields = '`'. implode('`, `', $func_get_args) .'`';
		return mysql_select_single("SELECT $fields FROM `caixa` WHERE `id` = '".$cashier_ID."' LIMIT 1;");
	} else 
	return false;
}

/*
getCashierStatus($cashier_ID);
Descrição: Retorna o status do caixa pelo id; 
*/
function getCashierStatus($cashier_ID) {
	$data = getCashierData($cashier_ID, 'status');

	return ($data !== false) ? $data['status'] : false;
}

/*
getCashierOpeningID();
Descrição: Retorna o id do caixa aberto; 
*/
function getCashierOpeningID() {
	$query = mysql_select_single("SELECT `id` FROM `caixa` WHERE `status` = '1';");

	return ($query !== false) ? $query['id'] : false;
}


/*
getCashierOpeningExist();
Descrição: Retorna o id do caixa aberto; 
*/
function getCashierOpeningExist() {
	$query = mysql_select_single("SELECT `id` FROM `caixa` WHERE `status` = '1';");

	return ($query !== false) ? true : false;
}


/*
getCashierOpeningCollaborator($cashier_ID);
Descrição: Retorna o id do usuário que abriu o caixa; 
*/
function getCashierOpeningCollaborator($cashier_ID) {
	$data = getCashierData($cashier_ID, 'colaborador_id_abertura');

	return ($data !== false) ? $data['colaborador_id_abertura'] : false;
}


/*
getCashierClosingCollaborator($cashier_ID);
Descrição: Retorna o id do usuário que fechou o caixa; 
*/
function getCashierClosingCollaborator($cashier_ID) {
	$data = getCashierData($cashier_ID, 'colaborador_id_fechamento');

	return ($data !== false) ? $data['colaborador_id_fechamento'] : false;
}


/*
getCashierOpeningHour($cashier_ID);
Descrição: Retorna com a hora que abriu o caixa; 
*/
function getCashierOpeningHour($cashier_ID) {
	$data = getCashierData($cashier_ID, 'horario_abertura');

	return ($data !== false) ? $data['horario_abertura'] : false;
}



/*
getCashierClosingHour($cashier_ID);
Descrição: Retorna com a hora que fechou o caixa; 
*/
function getCashierClosingHour($cashier_ID) {
	$data = getCashierData($cashier_ID, 'horario_fechamento');

	return ($data !== false) ? $data['horario_fechamento'] : false;
}


/*
getCashierOpeningDate($cashier_ID);
Descrição: Retorna com a data que abriu o caixa; 
*/
function getCashierOpeningDate($cashier_ID) {
	$data = getCashierData($cashier_ID, 'data_abertura');

	return ($data !== false) ? doDateConvert($data['data_abertura']) : false;
}

/*
getCashierClosingDate($cashier_ID);
Descrição: Retorna com a data que fechou o caixa; 
*/
function getCashierClosingDate($cashier_ID) {
	$data = getCashierData($cashier_ID, 'data_fechamento');

	return ($data !== false) ? doDateConvert($data['data_fechamento']) : false;
}


/*
getCashierOpeningValue($cashier_ID);
Descrição: Retorna com o valor que abriu o caixa; 
*/
function getCashierOpeningValue($cashier_ID) {
	$data = getCashierData($cashier_ID, 'valor_abertura');

	return ($data !== false) ? $data['valor_abertura'] : false;
}


/*
getCashierClosingValue($cashier_ID);
Descrição: Retorna com o valor que fechou o caixa; 
*/
function getCashierClosingValue($cashier_ID) {
	$data = getCashierData($cashier_ID, 'valor_fechamento');

	return ($data !== false) ? $data['valor_fechamento'] : false;
}

/*
getCashierBalanceMonth();
Descrição: Verifica o total ganho no mês atual
*/
function getCashierBalanceMonth() {
    /* INFORMAÇÕES DO MÊS ATUAL */
    $dateActual = array(
        'start' => date("Y-m-01"),
        'end' => date("Y-m-t")
    );
	$cA = 0;
	
    $countActual = mysql_select_single("SELECT SUM(`valor_total`) as `total_pagamentos` FROM `pagamentos` where `data_pagamento`>='".$dateActual['start']."' and `data_pagamento`<='".$dateActual['end']."' and `tipo_pagamento` > 0;");
    $cashActual = mysql_select_single("SELECT SUM(`valor_fechamento`) as `total_caixa` FROM `caixa` where `data_abertura`>='".$dateActual['start']."' and `data_abertura`<='".$dateActual['end']."' and `status`=0;");
	
	$cA = (int)$countActual['total_pagamentos'] + (int)$cashActual['total_caixa'];

    return number_format($cA, 2);
}

	/*****************************
	**
	**	FUNÇÕES IS
	**
	******************************/

/*
isCashierExist($cashier_ID);
Descrição: Verifica se o caixa existe pelo id, retorna true ou false; 
*/
function isCashierExist($cashier_ID) {
	$cashier_ID = sanitize($cashier_ID);

	$query = mysql_select_single("SELECT `id` FROM `caixa` WHERE `id`='$cashier_ID';");
	return ($query !== false) ? true : false;
}

/*
isCashierOpen();
Descrição: Verifica se existe caixa aberto, retorna true ou false
*/
function isCashierOpen() {
	return (getCashierOpeningExist() === true) ? true : false;
}

/*
isCashierClose();
Descrição: Verifica se existe caixa fechado, retorna true ou false
*/
function isCashierClose() {
	return (getCashierOpeningExist() === false) ? true : false;
}




	/*****************************
	**
	**	FUNÇÕES DO
	**
	******************************/

/*
doCashierOpening($user_ID, $value);
Descrição: Faz a abertura do caixa
*/
function doCashierOpening($user_ID, $value) {
	if (isCashierClose()) {
		mysql_insert("INSERT INTO `caixa` (`horario_abertura`, `valor_abertura`, `colaborador_id_abertura`, `status`, `data_abertura`) values ('".date("H:i:s")."', '".$value."', '".$user_ID."', 1, '".date("Y-m-d")."');");
	}
}


/*
doCashierClosing($user_ID, $value);
Descrição: Faz o fechamento do caixa
*/
function doCashierClosing($user_ID, $value) {
	if(isCashierOpen()) {
		mysql_update("UPDATE `caixa` SET `horario_fechamento`='".date("H:i:s")."', `valor_fechamento`='".$value."', `colaborador_id_fechamento`=".$user_ID.", `data_fechamento`='".date("Y-m-d")."', `status`=0 WHERE `status`=1;");
	}
}



/*
doCashierConvertStatusInString($n);
Descrição: Retorna o que o ID representa
*/
function doCashierConvertStatusInString($n) {
	
	return ($n == 1) ? 'ABERTO' : 'FECHADO'; 
}



?>
