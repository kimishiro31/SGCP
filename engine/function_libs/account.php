<?php


	/*****************************
	**
	**	FUNÇÕES GET
	**
	******************************/

/* getAccountData($user_id, campo1, campo2...);
// captura as colunas da tabela Account e joga num array
	MODO DE USO
	$account = getAccountData(account_ID, 'password', 'email');
	echo $account['email']; ele vai retorna com o email
*/
function getAccountData($account_id) {
	$data = array();
	$account_id = sanitize($account_id);
	
	$func_num_args = func_num_args();
	$func_get_args = func_get_args();
	
	if ($func_num_args > 1)  {
		unset($func_get_args[0]);
		
		$fields = '`'. implode('`, `', $func_get_args) .'`';
		return mysql_select_single("SELECT $fields FROM `contas` WHERE `id` = '".$account_id."' LIMIT 1;");
	} else 
	return false;
}

/*
getAccountStatus($account_ID);
Descrição: Verifica o status da conta, se a conta não existi retorna false 
*/
function getAccountStatus($account_ID) {
	
	$data = getAccountData($account_ID, 'status');

	return ($data !== false) ? $data['status'] : false;
}


/*
getAccountLogin($account_ID);
Descrição: Verifica o login da conta, se a conta não existi retorna false 
*/
function getAccountLogin($account_ID) {
	
	$data = getAccountData($account_ID, 'usuario');

	return ($data !== false) ? $data['usuario'] : false;
}

/*
getAccountPassword($account_ID);
Descrição: Verifica o password da conta, se a conta não existi retorna false 
*/
function getAccountPassword($account_ID) {
	
	$data = getAccountData($account_ID, 'senha');

	return ($data !== false) ? $data['senha'] : false;
}

/*
getAccountPPhone($account_ID);
Descrição: Verifica o principal telefone da conta, se a conta não existi retorna false 
*/
function getAccountPPhone($account_ID) {
	
	$data = getAccountData($account_ID, 'telefone');

	return ($data !== false) ? $data['telefone'] : false;
}

/*
getAccountSPhone($account_ID);
Descrição: Verifica o segundo telefone da conta, se a conta não existi retorna false 
*/
function getAccountSPhone($account_ID) {
	
	$data = getAccountData($account_ID, 'telefone_02');

	return ($data !== false) ? $data['telefone_02'] : false;
}


/*
getAccountEmail($account_ID);
Descrição: Verifica o segundo telefone da conta, se a conta não existi retorna false 
*/
function getAccountEmail($account_ID) {
	
	$data = getAccountData($account_ID, 'email');

	return ($data !== false) ? $data['email'] : false;
}


/*
getAccountDateCreated($account_ID);
Descrição: Verifica a data de criacao da conta, se a conta não existi retorna false 
*/
function getAccountDateCreated($account_ID) {
	
	$data = getAccountData($account_ID, 'criacao');

	return ($data !== false) ? doDateConvert($data['criacao']) : false;
}

/*
getAccountIPCreated($account_ID);
Descrição: Verifica a data de criacao da conta, se a conta não existi retorna false 
*/
function getAccountIPCreated($account_ID) {
	
	$data = getAccountData($account_ID, 'ip');

	return ($data !== false) ? $data['ip'] : false;
}


/*
getAccountGroup($account_ID);
Descrição: Verifica o nivel de acesso da conta, se a conta não existi retorna false 
*/
function getAccountGroup($account_ID) {
	
	$data = getAccountData($account_ID, 'nv_acesso');

	return ($data !== false) ? $data['nv_acesso'] : false;
}




/*
getLoginValidation($username, $password);
Descrição: Valida se o usuário e senha informado são da mesma conta, caso contrário retorna false
*/
function getLoginValidation($username, $password) {
	$username = sanitize($username);
	$password = sha1($password);
	
	$data = mysql_select_single("SELECT `id` FROM `contas` WHERE `usuario`='$username' AND `senha`='$password';");
	
	return ($data !== false) ? $data['id'] : false;
}



/*
getAccountID($user_ID);
Descrição: Retorna o ID da conta pelo ID do usuário, caso não existir retorna false
*/
function getAccountID($user_ID) {
	$query = mysql_select_single("select `contas`.`id` from `contas` INNER JOIN `usuarios` ON  `usuarios`.`conta_id` = `contas`.`id` where `usuarios`.`id`='".$user_ID."';");	

	return ($query !== false) ? $query['id'] : false;
}

	/*****************************
	**
	**	FUNÇÕES IS
	**
	******************************/

/*
getAccountExist($id);
Descrição: Verifica se a conta existe pelo id, retorna true ou false; 
*/
function isAccountExist($account_ID) {
	$account_ID = sanitize($account_ID);
	
	$account = mysql_select_single("SELECT `id` FROM `contas` WHERE `id`='$account_ID';");
	return ($account !== false) ? true : false;
}


/*
isAccountLoginExist($login);
Descrição: Verifica se o login existe, retorna true ou false; 
*/
function isAccountLoginExist($login) {
	$query = sanitize($login);
	
	$query = mysql_select_single("SELECT `id` FROM `contas` WHERE `usuario`='$login';");
	return ($query !== false) ? true : false;
}

/*
isAccountPasswordExist($password);
Descrição: Verifica se o password existe, retorna true ou false; 
*/
function isAccountPasswordExist($password) {
	$query = sanitize($password);
	
	$query = mysql_select_single("SELECT `id` FROM `contas` WHERE `senha`='$password';");
	return ($query !== false) ? true : false;
}


/*
isBlockedAccount($id);
Descrição: Verifica se a conta está bloqueada
*/
function isBlockedAccount($account_ID) {
	$id = sanitize($account_ID);

	return (getAccountStatus($account_ID) !== false && getAccountStatus($account_ID) == 0) ? true : false;
}


/*
doAccountConvertStatusInString($id);
Descrição: Verifica se a conta está bloqueada
*/
function doAccountConvertStatusInString($account_ID) {
	$data = getAccountData($account_ID, 'status');

	if($data !== false) {
		if($data['status'] == 0) 
			return 'DESATIVADO';
		elseif($data['status'] == 1)
			return 'ATIVADO';
	}
}

?>