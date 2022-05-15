<?php
$time = time();
if (!function_exists("elapsedTime")) {
	function elapsedTime($l_start = false, $l_time = false) {
		if ($l_start === false) global $l_start;
		if ($l_time === false) global $l_time;

		$l_time = explode(' ', microtime());
		$l_finish = $l_time[1] + $l_time[0];
		return round(($l_finish - $l_start), 4);
	}
}

// Faz a conexão com o banco de dados
$connect = new mysqli($config['sqlHost'], $config['sqlUser'], $config['sqlPassword'], $config['sqlDatabase']);
/*
// Verifica se a conexão falhou, se falhar ele manda para a página de instalação para refazer.
if ($connect->connect_errno) {
	header('Location: ../install/index.php');
}*/

// mysql_create_table($table);
// cria uma nova tabela no banco de dados
function mysql_create_table($table) {
	global $connect;
	mysqli_query($connect, 'create table if not exists '.$table.' (any varchar(255));') or die(var_dump($table)."<br>(query - <font color='red'>SQL error</font>) <br><br><br>".mysqli_error($connect));
}

// mysql_truncate_table($table);
// ele faz uma limpeza no registro de uma tabela;
function mysql_truncate_table($table) {
	global $connect;
	mysqli_query($connect, 'TRUNCATE '.$table.';') or die(var_dump($table)."<br>(query - <font color='red'>SQL error</font>) <br><br><br>".mysqli_error($connect));
}

// doMysqlEscapeSstring($escapestr);
// faz uma formatação na string para ficar viavel para mysql
function doMysqlEscapeSstring($escapestr) {
	global $connect;
	return mysqli_real_escape_string($connect, $escapestr);
}

// mysql_insert_multi($query);
// faz a inserção de várias tabelas no database
function mysql_insert_multi($query) {
	global $connect;
	mysqli_multi_query($connect, $query) or die(var_dump($query)."<br>(query - <font color='red'>SQL error</font>) <br><br><br>".mysqli_error($connect));
}

// mysql_getlast_insert();
// faz a verificação do ultimo valor inserido na query utilizada anteriormente
function mysql_getlast_insert() {

	global $connect;
	global $aacQueries;
	$aacQueries++;
	global $accQueriesData;
	
	$query = "SELECT  LAST_INSERT_ID();";

	$accQueriesData[] = "[" . elapsedTime() . "] " . $query;
	$result = mysqli_query($connect,$query) or die(var_dump($query)."<br>(query - <font color='red'>SQL error</font>) <br>Type: <b>select_single</b> (select single row from database)<br><br>".mysqli_error($connect));
	$row = mysqli_fetch_assoc($result);


	return ($row != 0) ? $row['LAST_INSERT_ID()'] : false;
}

// mysql_select_single($query);
// faz um select e insere em um array os dados
function mysql_select_single($query) {
	global $connect;
	global $aacQueries;
	$aacQueries++;

	global $accQueriesData;
	$accQueriesData[] = "[" . elapsedTime() . "] " . $query;
	$result = mysqli_query($connect,$query) or die(var_dump($query)."<br>(query - <font color='red'>SQL error</font>) <br>Type: <b>select_single</b> (select single row from database)<br><br>".mysqli_error($connect));
	$row = mysqli_fetch_assoc($result);
	return !empty($row) ? $row : false;
}

// mysql_select_multi($query);
// faz varios select e insere em um array os dados
function mysql_select_multi($query){
	global $connect;
	global $aacQueries;
	$aacQueries++;
	global $accQueriesData;
	$accQueriesData[] = "[" . elapsedTime() . "] " . $query;
	$array = array();
	$results = mysqli_query($connect,$query) or die(var_dump($query)."<br>(query - <font color='red'>SQL error</font>) <br>Type: <b>select_multi</b> (select multiple rows from database)<br><br>".mysqli_error($connect));
	while($row = mysqli_fetch_assoc($results)) {
		$array[] = $row;
	}
	return !empty($array) ? $array : false;
}


// dbResert();
// limpa todos os dados da database;
function dbResert() {
mysql_update("SET foreign_key_checks = 0;");
mysql_create_table('accounts');
mysql_truncate_table('accounts');
mysql_update("SET foreign_key_checks = 1;");
mysql_insert("INSERT INTO `accounts` (`id`, `name`, `password`, `salt`, `premdays`, `lastday`, `email`, `old_email`, `key`, `blocked`, `warnings`, `group_id`, `premium_points`, `created`, `create_ip`, `flag`, `active`, `activekey`) VALUES (1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', '', '1500', '0', 'admin@admin.com.br', '', '1', '0', '0', '6', '10000', NULL, '0', '1', '1', '0');");
}

// outros...
// - mysql update
function mysql_update($query){ voidQuery($query); }

// mysql insert
function mysql_insert($query){ voidQuery($query); }

// mysql delete
function mysql_delete($query){ voidQuery($query); }

// Send a void query
function voidQuery($query) {
	global $connect;
	global $aacQueries;
	$aacQueries++;
	global $accQueriesData;
	$accQueriesData[] = "[" . elapsedTime() . "] " . $query;
	mysqli_query($connect,$query) or die(var_dump($query)."<br>(query - <font color='red'>SQL error</font>) <br>Type: <b>voidQuery</b> (voidQuery is used for update, insert or delete from database)<br><br>".mysqli_error($connect));
}
?>
