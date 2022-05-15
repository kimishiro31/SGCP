<?php
/* getLoggedIn();
Descrição: Verifica se o usuário esta logado
*/
function getLoggedIn() {
	return (getSession('user_id') !== false) ? true : false;
}

/* isUserExist($id);
Descrição: Verifica se o usuário existe
*/
function isUserExist($user_ID) {
	$user_ID = sanitize($user_ID);
	
	$user = mysql_select_single("SELECT `id` FROM `usuarios` WHERE `id`='$user_ID';");
	return ($user !== false) ? $user['id'] : false;
}

/* isUserCPFExist($id);
Descrição: Verifica se o cpf existe
*/
function isUserCPFExist($cpf) {
	$cpf = sanitize($cpf);
	
	$user = mysql_select_single("SELECT `id` FROM `usuarios` WHERE `cpf`='$cpf';");
	return ($user !== false) ? true : false;
}

/* getUsersData($user_ID, tabela1, tabela2);
Descrição: Ele busca em uma tabela e retorna as colunas selecionadas em um array
MODO DE USO:
	$user = getUsersData(usuario_id, 'password', 'email');
	echo $user['email']; ele vai retorna com o email
*/
function getUsersData($user_ID) {
	$data = array();
	$user_ID = sanitize($user_ID);
	
	$func_num_args = func_num_args();
	$func_get_args = func_get_args();
	
	if ($func_num_args > 1)  {
		unset($func_get_args[0]);
		
		$fields = '`'. implode('`, `', $func_get_args) .'`';
		return mysql_select_single("SELECT $fields FROM `usuarios` WHERE `id` = '".$user_ID."' LIMIT 1;");
	} else return false;
}

/* getUserFirstName($user_ID);
Descrição: Busca o usuário e retorna o primeiro nome, caso o usuário não existir retornar false;
*/
function getUserFirstName($user_ID) {
	$data = getUsersData($user_ID, 'primeiro_nome');

	return ($data !== false) ? $data['primeiro_nome'] : false;
}

/* getUserLastName($user_ID);
Descrição: Busca o usuário e retorna o ultimo nome, caso o usuário não existir retornar false;
*/
function getUserLastName($user_ID) {
	$data = getUsersData($user_ID, 'ultimo_nome');
	$last = trim($data['ultimo_nome']);

	return ($data !== false) ? substr($last, strrpos($last, ' ')) : false;
}

/* getUserCompleteName($user_ID);
Descrição: Busca o usuário e retorna o nome completo, caso o usuário não existir retornar false;
*/
function getUserCompleteName($user_ID) {
	$data = getUsersData($user_ID, 'primeiro_nome', 'ultimo_nome');

	return ($data !== false) ? $data['primeiro_nome'].' '.$data['ultimo_nome'] : false;
}

/* getUserBirthDate($user_ID);
Descrição: Busca o usuário e retorna a data de nascimento, caso o usuário não existir retornar false;
*/
function getUserBirthDate($user_ID) {
	$data = getUsersData($user_ID, 'data_nascimento');

	return ($data !== false) ? doDateConvert($data['data_nascimento']) : false;
}

/* getUserID($cpf);
Descrição: Busca o usuário pelo cpf e retorna o id, caso o usuário não existir retornar false;
*/
function getUserID($cpf) {
	$cpf = sanitize($cpf);
	$data = mysql_select_single("SELECT `id` FROM `usuarios` WHERE `cpf`='$cpf' LIMIT 1;");
	
	return ($data !== false) ? $data['id'] : false;
}


/* getUserCPF($id);
Descrição: Busca o usuário pelo id e retorna o cpf, caso o usuário não existir retornar false;
*/
function getUserCPF($user_ID) {
	$data = getUsersData($user_ID, 'cpf');

	return ($data !== false) ? $data['cpf'] : false;
}

/* getUserRG($id);
Descrição: Busca o usuário pelo id e retorna o RG, caso o usuário não existir retornar false;
*/
function getUserRG($user_ID) {
	$data = getUsersData($user_ID, 'rg');

	return ($data !== false) ? $data['rg'] : false;
}

/* getUserGender($id);
Descrição: Busca o usuário pelo id e retorna o genero, caso o usuário não existir retornar false;
*/
function getUserGender($user_ID) {
	$data = getUsersData($user_ID, 'genero');

	return ($data !== false) ? $data['genero'] : false;
}


/* getUserAddressStreet($id);
Descrição: Busca o usuário pelo id e retorna o rua, caso o usuário não existir retornar false;
*/
function getUserAddressStreet($user_ID) {
	$data = getUsersData($user_ID, 'rua');

	return ($data !== false) ? $data['rua'] : false;
}

/* getUserAddressComplement($id);
Descrição: Busca o usuário pelo id e retorna o complemento do endereço, caso o usuário não existir retornar false;
*/
function getUserAddressComplement($user_ID) {
	$data = getUsersData($user_ID, 'complemento');

	return ($data !== false) ? $data['complemento'] : false;
}


/* getUserAddressDistrict($id);
Descrição: Busca o usuário pelo id e retorna o bairro, caso o usuário não existir retornar false;
*/
function getUserAddressDistrict($user_ID) {
	$data = getUsersData($user_ID, 'bairro');

	return ($data !== false) ? $data['bairro'] : false;
}

/* getUserAddressNumber($id);
Descrição: Busca o usuário pelo id e retorna o numero, caso o usuário não existir retornar false;
*/
function getUserAddressNumber($user_ID) {
	$data = getUsersData($user_ID, 'numero');

	return ($data !== false) ? $data['numero'] : false;
}

/* getUserAddressCEP($id);
Descrição: Busca o usuário pelo id e retorna o cep, caso o usuário não existir retornar false;
*/
function getUserAddressCEP($user_ID) {
	$data = getUsersData($user_ID, 'cep');

	return ($data !== false) ? $data['cep'] : false;
}

/* getUserAddressCity($id);
Descrição: Busca o usuário pelo id e retorna o cidade, caso o usuário não existir retornar false;
*/
function getUserAddressCity($user_ID) {
	$data = getUsersData($user_ID, 'cidade');

	return ($data !== false) ? $data['cidade'] : false;
}

/* getUserAddressUF($id);
Descrição: Busca o usuário pelo id e retorna o estado, caso o usuário não existir retornar false;
*/
function getUserAddressUF($user_ID) {
	$data = getUsersData($user_ID, 'estado');

	return ($data !== false) ? $data['estado'] : false;
}

/* getUserNacionality($id);
Descrição: Busca o usuário pelo id e retorna o Nacionalidade, caso o usuário não existir retornar false;
*/
function getUserNacionality($user_ID) {
	$data = getUsersData($user_ID, 'nacionalidade');

	return ($data !== false) ? $data['nacionalidade'] : false;
}

/* getUserProfission($id);
Descrição: Busca o usuário pelo id e retorna o Profissao, caso o usuário não existir retornar false;
*/
function getUserProfission($user_ID) {
	$data = getUsersData($user_ID, 'profissao');

	return ($data !== false) ? $data['profissao'] : false;
}

/* getUserPhotoName($id);
Descrição: Busca o usuário pelo id e retorna o FOTO, caso o usuário não existir retornar false;
*/
function getUserPhotoName($user_ID) {
	$data = getUsersData($user_ID, 'foto');

	return ($data !== false) ? $data['foto'] : false;
}

/* \/ FALTA REVISAR */































/* deleteUser($id);
Descrição: Deleta os dados do usuário pelo id;
*/
function deleteUser($id) {
	$delete = getUsersData(getUserID(getUserCPF($id)), 'conta_id');
	$account_id = sanitize($delete['conta_id']);
//	doDeleteFolder(getUserFolder(getUserCPF($id)));
	mysql_delete("DELETE FROM `agendamentos` WHERE `usuario_id`='$id';");
	mysql_delete("DELETE FROM `usuarios_gabarito` WHERE `usuario_id`='$id';");
	mysql_delete('DELETE FROM `contas` WHERE `id`='.$account_id.';');
	mysql_delete("DELETE FROM `usuarios` WHERE `id`='$id';");
}

/* doCreateUser($register_data);
Descrição: Faz a criação da CONTA e do USUÁRIO
*/
function doCreateUser($register_data) {
	array_walk($register_data, 'array_sanitize');
	$register_data['password'] = sha1($register_data['password']);
	$ip = $register_data['ip'];
	$created = $register_data['created'];
	unset($register_data['ip']);
	unset($register_data['created']);
	doCreateUserFolder($register_data['cpf']);
	
	mysql_insert("INSERT INTO `contas` (`usuario`, `senha`, `telefone`, `telefone_02`, `email`, `criacao`, `ip`) VALUES ('".$register_data['username']."', '".$register_data['password']."', '".$register_data['phoneP']."', '".$register_data['phoneS']."', '".$register_data['email']."', '".$created."', '".$ip."')");
	$lastID = mysql_getlast_insert();
	mysql_insert("INSERT INTO `usuarios` (`conta_id`, `primeiro_nome`, `ultimo_nome`, `data_nascimento`, `cpf`, `rg`, `genero`, `rua`, `bairro`, `numero`, `complemento`, `cep`, `cidade`, `estado`, `nacionalidade`, `profissao`, `foto`) VALUES ('".$lastID."', '".$register_data['firstName']."', '".$register_data['lastName']."', '".$register_data['bithDate']."', '".$register_data['cpf']."', '".$register_data['rg']."', '".$register_data['gender']."', '".$register_data['ad_rua']."', '".$register_data['ad_bairro']."', '".$register_data['ad_num']."', '".$register_data['ad_comp']."', '".$register_data['ad_cep']."', '".$register_data['ad_cid']."', '".$register_data['ad_estado']."', '".$register_data['nacionality']."', '".$register_data['profission']."', 'profile.png')");

	
	if(is_file('layout/images/profile_null.png')) {
		copy('layout/images/profile_null.png', getUserFolder($register_data['cpf']).'/profile.png');
	}

}


/* doUpdateUser($update_data);
Descrição: Faz a alteração dos dados da CONTA e do USUÁRIO
*/
function doUpdateUser($register_data) {
	$photo = $register_data['photo'];
	unset($register_data['photo']);
	array_walk($register_data, 'array_sanitize');
	$user_data = getUsersData($register_data['user_id'], 'conta_id', 'cpf');

	$image_data = array(
		'name' => $photo['name'], 
		'tmp' => $photo['tmp_name'], 
		'size' => $photo['size'], 
		'type' => $photo['type'],
		'new_name' => 'profile.'.pathinfo($photo['name'], PATHINFO_EXTENSION), 
		);

	$import_data_account = array(
		'usuario'	 	=>	$register_data['username'],
		'senha' 		=> $register_data['password'],
		'telefone' 		=> $register_data['phoneP'],
		'telefone_02' 	=> $register_data['phoneS'],
		'email' 		=> $register_data['email'],
		'nv_acesso' 	=> $register_data['group'],
		'status' 		=> $register_data['status'],
	);


	$import_data_user = array(
		'PRIMEIRO_NOME'	 	=>	$register_data['firstName'],
		'ULTIMO_NOME' 		=> $register_data['lastName'],
		'DATA_NASCIMENTO' 	=> $register_data['bithDate'],
		'RG' 				=> $register_data['rg'],
		'GENERO' 			=> $register_data['gender'],
		'RUA' 				=> $register_data['ad_rua'],
		'BAIRRO' 			=> $register_data['ad_bairro'],
		'NUMERO' 			=> $register_data['ad_num'],
		'COMPLEMENTO' 		=> $register_data['ad_comp'],
		'CEP' 				=> $register_data['ad_cep'],
		'CIDADE' 			=> $register_data['ad_cid'],
		'ESTADO' 			=> $register_data['ad_estado'],
		'NACIONALIDADE' 	=> $register_data['nacionality'],
		'PROFISSAO' 		=> $register_data['profission'],
		'FOTO' 				=> ($image_data['size'] > 0) ? $image_data['new_name'] : ''
	);

	if(empty($import_data_account['senha'])) {
		unset($import_data_account['senha']);
	}
	else {
		$import_data_account['senha'] = sha1($import_data_account['senha']);
	}

	if(empty($import_data_account['telefone_02']))
		unset($import_data_account['telefone_02']);
	if(empty($import_data_account['email']))
		unset($import_data_account['email']);

	if(empty($import_data_user['ad_comp']))
		unset($import_data_user['ad_comp']);
	if(empty($import_data_user['nacionality']))
		unset($import_data_user['nacionality']);
	if(empty($import_data_user['profission']))
		unset($import_data_user['profission']);
	if(($import_data_user['FOTO']) === '')
		unset($import_data_user['FOTO']);


		doCreateUserFolder($user_data['cpf']);

	$old_image = mysql_select_single("SELECT `id`, `foto` from `usuarios` where `id`='".$register_data['user_id']."'");
	if($old_image['foto'] !== '' && (isset($import_data_user['FOTO'])) && is_file('engine/users/images/'.md5($user_data['cpf']).'/'.$old_image['foto'])) {
		unlink(getUserFolder($user_data['cpf']).'/'.$old_image['foto']);
	}

	$upload = move_uploaded_file($image_data["tmp"], getUserFolder($user_data['cpf']).'/'.$image_data["new_name"]);

	$account_sql = doConvertUpdateSQLFormat($import_data_account);
	$users_sql = doConvertUpdateSQLFormat($import_data_user);
	mysql_update("UPDATE `usuarios` SET $users_sql WHERE `id`='".$register_data['user_id']."';");
	mysql_update("UPDATE `contas` SET $account_sql WHERE `id`='".$user_data['conta_id']."';");
}


function getExistTypePay($id) {
	$id = (int)$id;

	$query = mysql_select_single("SELECT `id` FROM `tipos_pagamento` WHERE `id`=$id;");

	return ($query !== false) ? $query['id'] : false;
}

function doTotalUsersCommon() {
	$query = mysql_select_single("SELECT COUNT(*) as `total_usuarios` FROM `usuarios` INNER JOIN `contas` ON `usuarios`.`conta_id` = `contas`.`id` where `contas`.`nv_acesso`=0;");
	

	return str_pad($query['total_usuarios'] , 2, '0' , STR_PAD_LEFT);;
}

?>
