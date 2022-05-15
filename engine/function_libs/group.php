<?php

/*
getGroup($account_ID);
Descrição: Verifica o nivel de acesso do usuário
*/
function getGroup($account_ID) {
	$account_ID = sanitize($account_ID);
	$data = getAccountData($account_ID, 'nv_acesso');

	return ($data !== false) ? $data['nv_acesso'] : false;
}


/*
doGroupConvertInString($group_ID);
Descrição: Retorna o group por extenso
*/
function doGroupConvertInString($group_ID) {
	$groups = config('groups');

	return $groups[$group_ID];
}


// isCommon($id);
// Verifica se o usuário é um usuário comum
function isCommon($account_ID) {
	$group = getGroup($account_ID);

	return ($group !== false && $group <= 0) ? true : false; 
}


// isAttendant($id);
// Verifica se o usuário é um atendente
function isAttendant($account_ID) {
	$group = getGroup($account_ID);

	return ($group !== false && $group == 1) ? true : false; 
}

// isManager($account_ID);
// Verifica se o usuário é um usuário Gerente/Dono
function isManager($account_ID) {
	$group = getGroup($account_ID);

	return ($group !== false && $group == 2) ? true : false; 
}


// isAdmin($account_ID);
// Verifica se o usuário é um usuário administrador
function isAdmin($account_ID) {
	$group = getGroup($account_ID);

	return ($group !== false && $group >= 3) ? true : false; 
}


?>

