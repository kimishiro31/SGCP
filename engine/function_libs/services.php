<?php


/* getServiceData($service_ID, campo1, campo2...);
// captura as colunas da tabela Account e joga num array
	MODO DE USO
	$account = getServiceData(scheduled_ID, 'password', 'email');
	echo $account['email']; ele vai retorna com o email
*/
function getServiceData($service_ID) {
	$data = array();
	$service_ID = sanitize($service_ID);
	
	$func_num_args = func_num_args();
	$func_get_args = func_get_args();
	
	if ($func_num_args > 1)  {
		unset($func_get_args[0]);
		
		$fields = '`'. implode('`, `', $func_get_args) .'`';
		return mysql_select_single("SELECT $fields FROM `servicos` WHERE `id` = '".$service_ID."' LIMIT 1;");
	} else 
	return false;
}

/*
isServiceExist($service_ID)
Descrição: Verifica se existe o servico, retorna true ou false
*/
function isServiceExist($service_ID) {
	$query = mysql_select_single("SELECT `id` FROM `servicos` WHERE `id`='$service_ID';");

	return ($query) ? true : false;
}



function doListServices() {
	$query = mysql_select_multi("SELECT * FROM `servicos`");

	return ($query) ? $query : false;
}


function doAddService($description, $priority, $value, $time) {
	$description = (string)$description;
	$priority = (int)$priority;
	$value = (int)$value;
	$time = (string)$time;

	mysql_insert("INSERT INTO `servicos` (`descricao`, `valor`, `tempo`, `prioridade`) VALUES ('".$description."', '".$value."', '".$time."', '".$priority."');");
}

function doUpdateService($service_ID, $description, $priority, $value, $time) {
	$service_ID = (int)$service_ID;
	$description = (string)$description;
	$priority = (int)$priority;
	$value = (int)$value;
	$time = (string)$time;

	mysql_update("UPDATE `servicos` SET `descricao`='".$description."', `valor`='".$value."', `tempo`='".$time."', `prioridade`='".$priority."' WHERE `id`='".$service_ID."'; ");
}


function doRemoveService($service_ID) {
	$service_ID = (int)$service_ID;
	mysql_delete("DELETE FROM `servicos` WHERE `id`='$service_ID';");
}


function getServiceDescription($service_ID) {
	$data = getServiceData($service_ID, 'descricao');

	return ($data) ? $data['descricao'] : false;
}

function getServiceValue($service_ID) {
	$data = getServiceData($service_ID, 'valor');

	return ($data) ? $data['valor'] : false;
}

function getServicePriority($service_ID) {
	$data = getServiceData($service_ID, 'prioridade');

	return ($data) ? $data['prioridade'] : false;
}

function getServiceTime($service_ID) {
	$data = getServiceData($service_ID, 'tempo');

	return ($data) ? $data['tempo'] : false;
}

?>

