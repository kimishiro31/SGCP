<?php



/* getPathologyData($pathology_ID, campo1, campo2...);
// captura as colunas da tabela Account e joga num array
	MODO DE USO
	$account = getPathologyData(pathology_ID, 'password', 'email');
	echo $account['email']; ele vai retorna com o email
*/
function getPathologyData($pathology_ID) {
	$data = array();
	$pathology_ID = sanitize($pathology_ID);
	
	$func_num_args = func_num_args();
	$func_get_args = func_get_args();
	
	if ($func_num_args > 1)  {
		unset($func_get_args[0]);
		
		$fields = '`'. implode('`, `', $func_get_args) .'`';
		return mysql_select_single("SELECT $fields FROM `patologias` WHERE `id` = '".$pathology_ID."' LIMIT 1;");
	} else 
	return false;
}

/*
isPathologyExist($pathology_ID)
Descrição: Verifica se existe a patologia, retorna true ou false
*/
function isPathologyExist($pathology_ID) {
	$query = mysql_select_single("SELECT `id` FROM `patologias` WHERE `id`='$pathology_ID';");

	return ($query) ? true : false;
}

/*
getPathologyName($pathology_ID);
Descrição: Retorna o nome da patologia
*/
function getPathologyName($pathology_ID) {
	$data = getPathologyData($pathology_ID, 'nome');

	return ($data) ? $data['nome'] : false;
}


/*
getPathologyType($pathology_ID);
Descrição: Retorna a prioridade da patologia
*/
function getPathologyType($pathology_ID) {
	$data = getPathologyData($pathology_ID, 'type');

	return ($data) ? $data['type'] : false;
}


/*
doAddPathology($description, $priority)
Descrição: Adiciona uma nova patologia ao banco de dados
*/
function doAddPathology($description, $priority) {
	$description = sanitize($description);
	$priority = sanitize($priority);

	mysql_insert("INSERT INTO `patologias` (`nome`, `type`) VALUES ('".$description."', '".$priority."');");
}


/*
doRemovePathology($id)
Descrição: Remove a patologia do banco de dados
*/
function doRemovePathology($pathology_ID) {
	$pathology_ID = sanitize($pathology_ID);
	mysql_delete("DELETE FROM `patologias` WHERE `id`='$pathology_ID';");
}




/*
doAddUserPathology($user_id, $pe, $pat_n, $pat_id, $pat_pos, $pat_type)
Descrição: O comando faz a inserção da patologia que o paciente tem, se caso já tiver sido preenchida ele faz a substituição.
*/
function doAddUserPathology($user_id, $pe, $pat_n, $pat_id, $pat_pos, $pat_type) {
    if(getUserPathologyID($user_id, $pat_n, $pat_type) === false)
        return mysql_insert("INSERT INTO `usuarios_patologias` (`usuario_id`, `pe`, `pat_n`, `pat_id`, `pat_pos`, `pat_type`) VALUES (".$user_id.", '".$pe."', ".$pat_n.", ".$pat_id.", ".$pat_pos.", '".$pat_type."');");				
    else
        return mysql_update("UPDATE `usuarios_patologias` SET `pat_id`=".$pat_id.", `pat_pos`='".$pat_pos."' WHERE `usuario_id`=".$user_id." and `pat_n`=".$pat_n." and `pat_type`='".$pat_type."';");
}


/*
getUserPathologyID($user_id, $n, $type)
Descrição: Retorna o ID da patologia que foi preenchido no usuário, posição, tipo[VD ou VP]
VD = Visão Dorsal
VP = Visão Plantar
*/
function getUserPathologyID($user_id, $n, $type) {
	$query = mysql_select_single("SELECT `id` FROM `usuarios_patologias` WHERE `pat_n`='$n' and `usuario_id`='$user_id' and `pat_type` = '$type';");

	return ($query) ? $query['id'] : false;
}


/*
getUserPathologyPos($user_id, $n, $type)
Descrição: Verifica a POS da patologia que foi preenchido no usuário, posição, tipo[VD ou VP]
VD = Visão Dorsal
VP = Visão Plantar
*/
function getUserPathologyPos($user_id, $n, $type) {
	$query = mysql_select_single("SELECT `pat_pos` FROM `usuarios_patologias` WHERE `pat_n`='$n' and `usuario_id`='$user_id' and `pat_type`='$type';");

	return ($query) ? $query['pat_pos'] : false;
}

/*
getUserPathologyValue($user_id, $n, $type)
Descrição: Retorna o ID do tipo da patologia que foi preenchido no usuário, posição, tipo[VD ou VP]
VD = Visão Dorsal
VP = Visão Plantar
*/
function getUserPathologyValue($user_id, $n, $type) {
	$query = mysql_select_single("SELECT `pat_id` FROM `usuarios_patologias` WHERE `pat_n`='$n' and `usuario_id`='$user_id' and `pat_type`='$type';");

	return ($query) ? $query['pat_id'] : false;
}

/*
doListPathologys()
Descrição: Retorna uma lista de todas as patologias existentes
*/
function doListPathologys() {
	$query = mysql_select_multi("SELECT * FROM `patologias`");

	return ($query) ? $query = array_combine(range(1, count($query)), array_values($query)) : false;
}

?>

