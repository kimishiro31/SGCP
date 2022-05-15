<?php 


/* getPaymentData($pay_ID, campo1, campo2...);
// captura as colunas da tabela Account e joga num array
	MODO DE USO
	$account = getPaymentData(pay_ID, 'password', 'email');
	echo $account['email']; ele vai retorna com o email
*/
function getPaymentData($pay_ID) {
	$data = array();
	$pay_ID = sanitize($pay_ID);
	
	$func_num_args = func_num_args();
	$func_get_args = func_get_args();
	
	if ($func_num_args > 1)  {
		unset($func_get_args[0]);
		
		$fields = '`'. implode('`, `', $func_get_args) .'`';
		return mysql_select_single("SELECT $fields FROM `pagamentos` WHERE `id` = '".$pay_ID."' LIMIT 1;");
	} else 
	return false;
}


/*
isPaymentExist($pay_ID)
Descrição: Verifica se o pagamento existe, retorna true ou false
*/
function isPaymentExist($pay_ID) {
	$query = mysql_select_single("SELECT `id` FROM `pagamentos` WHERE `id`='$pay_ID';");

	return ($query) ? true : false;
}


/*
getPaymentID($scheduled_ID)
Descrição: Retorna o id do pagamento pelo id do agendamento, se não existir retorna false
*/
function getPaymentID($scheduled_ID) {
	$query = mysql_select_single("SELECT `id` FROM `pagamentos` WHERE `agendamento_id`='$scheduled_ID';");

	return ($query) ? $query['id'] : false;
}


/*
getPaymentScheduleID($pay_ID)
Descrição: Retorna o ID do agendamento, se não existir pagamento retorna false
*/
function getPaymentScheduleID($pay_ID) {
	$data = getPaymentData($pay_ID, 'agendamento_id');

	return ($data) ? $data['agendamento_id'] : false;
}


/*
getPaymentValue($pay_ID)
Descrição: Retorna o Valor do pagamento, se não existir pagamento retorna false
*/
function getPaymentValue($pay_ID) {
	$data = getPaymentData($pay_ID, 'valor_total');

	return ($data) ? $data['valor_total'] : false;
}


/*
getPaymentType($pay_ID)
Descrição: Retorna o Tipo do pagamento, se não existir pagamento retorna false
*/
function getPaymentType($pay_ID) {
	$data = getPaymentData($pay_ID, 'tipo_pagamento');

	return ($data) ? (int)$data['tipo_pagamento'] : false;
}



/*
getPaymentDate($pay_ID)
Descrição: Retorna a Data do pagamento, se não existir pagamento retorna false
*/
function getPaymentDate($pay_ID) {
	$data = getPaymentData($pay_ID, 'data_pagamento');

	return ($data) ? doDateConvert($data['data_pagamento']) : false;
}


/*
getPaymentTime($pay_ID)
Descrição: Retorna a Hora do pagamento, se não existir pagamento retorna false
*/
function getPaymentTime($pay_ID) {
	$data = getPaymentData($pay_ID, 'hora_pagamento');

	return ($data) ? $data['hora_pagamento'] : false;
}

/*
getPaymentCashier($pay_ID)
Descrição: Retorna o ID do caixa que o pagamento foi creditado, se não existir pagamento retorna false
*/
function getPaymentCashier($pay_ID) {
	$data = getPaymentData($pay_ID, 'caixa_id');

	return ($data) ? $data['caixa_id'] : false;
}

/*
doPaymentsList($cashier_ID)
Descrição: Verifica todos os pagamentos que foi efetuado na abertura de caixa X
*/
function doPaymentsList($cashier_ID) {
	$query = mysql_select_multi("SELECT * from `pagamentos` where `caixa_id`= '".$cashier_ID."';");

	return ($query !== false) ? $query : false;
}


/*
getPaymentsValueOfDay($cashier_ID, $pay_type)
Descrição: verifica o valor total que foi arrecadado desde a abertura de caixa
1 = DINHEIRO
2 = DEBITO
3 = CRÉDITO
4 = PIX
*/
function getPaymentsValueOfDay($cashier_ID, $pay_type) {
	$query = doPaymentsList($cashier_ID);
	$som = 0;

	if($query !== false) {
		foreach($query as $key => $value) {
			if($value['tipo_pagamento'] == $pay_type) 
				$som += $value['valor_total'];
		}
	}

	return number_format($som, 2);
}


/*
getPayType($pay_ID);
retorna com o tipo de pagamento
*/
function getPayType($pay_ID) {
	$query = mysql_select_single("SELECT `descricao` from `tipos_pagamento` where `id`='".$pay_ID."';");

	return ($query !== false) ? $query['descricao'] : false;
}




















?>