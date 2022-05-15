<?php



/* getAttendanceData($attendance_ID, campo1, campo2...);
// captura as colunas da tabela Account e joga num array
	MODO DE USO
	$account = getAttendanceData(attendance_ID, 'password', 'email');
	echo $account['email']; ele vai retorna com o email
*/
function getAttendanceData($attendance_ID) {
	$data = array();
	$attendance_ID = sanitize($attendance_ID);
	
	$func_num_args = func_num_args();
	$func_get_args = func_get_args();
	
	if ($func_num_args > 1)  {
		unset($func_get_args[0]);
		
		$fields = '`'. implode('`, `', $func_get_args) .'`';
		return mysql_select_single("SELECT $fields FROM `atendimentos_realizado` WHERE `id` = '".$attendance_ID."' LIMIT 1;");
	} else 
	return false;
}

/*
isAttendanceExist($attendance_ID)
Descrição: Verifica se existe o Atendimento, retorna true ou false
*/
function isAttendanceExist($attendance_ID) {
	$query = mysql_select_single("SELECT `id` FROM `atendimentos_realizado` WHERE `id`='$attendance_ID';");

	return ($query) ? true : false;
}

/*
getAttendanceID($schedule_ID)
Descrição: Verifica se existe o Atendimento pelo numero do agendamento, retorna id do atendimento ou false
*/
function getAttendanceID($schedule_ID) {
	$query = mysql_select_single("SELECT `id` FROM `atendimentos_realizado` WHERE `agendamento_id`='$schedule_ID';");

	return ($query) ? $query['id'] : false;
}

/*
getAttendanceScheduledID($attendance_ID)
Descrição: Retorna o ID do agendamento do atendimento
*/
function getAttendanceScheduledID($attendance_ID) {
	$data = getAttendanceData($attendance_ID, 'agendamento_id');

	return ($data !== false) ? $data['agendamento_id'] : false;
}

/*
getAttendanceDate($attendance_ID)
Descrição: Retorna o ID do agendamento do atendimento
*/
function getAttendanceDate($attendance_ID) {
	$data = getAttendanceData($attendance_ID, 'data_confirmacao');

	return ($data !== false) ? $data['data_confirmacao'] : false;
}

/*
getAttendanceDescription($attendance_ID)
Descrição: Retorna o ID do agendamento do atendimento
*/
function getAttendanceDescription($attendance_ID) {
	$data = getAttendanceData($attendance_ID, 'observacao');

	return ($data !== false) ? $data['observacao'] : false;
}

?>