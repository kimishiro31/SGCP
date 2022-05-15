<?php

/* ****************************
   **** SERVIÇO JA REFEITO ****
   ****************************
*/



/*
doConvertPriorityToString($n)
Descrição: Converte ID da prioridade para extenso
*/
function doConvertPriorityToString($n) {
	$n = ($n <= 0 || $n >= 4) ? $n = 1 : $n;

	$gravity = array(
		1 => 'baixa',
		2 => 'media',
		3 => 'alta'
	);

	return $gravity[$n];  
}


/* doCPFValidation($cpf);
Descrição: Verifica se o CPF é valido
*/
function doCPFValidation($cpf) {
	$crescent = 2; // contador inicial
	$spd = 0; // soma do primeiro digito
	$ssd = 0; // soma do segundo digito

	// Valida se a string é numero | se tem 11 caracteres | verifica se a sequencia é de mesmo numero
	if(is_numeric($cpf) === false || strlen($cpf) < 1 || strlen($cpf) > 11 || isSameCharacter($cpf))
		return false;

	// faz a soma necessária para os calculos
	for($cn = 8; $cn >= 0; --$cn, ++$crescent) {
		$spd += ($crescent * $cpf[$cn]);
		$ssd += (($crescent+1) * $cpf[$cn]);
	}
	
	// verifica quais as numeração do primeiro e segundo digito
	$pd = ($spd % 11 < 2) ? 0 : (11 - ($spd % 11));
	$sd = (($ssd + ($pd * 2)) % 11 < 2) ? 0 : (11 - (($ssd + ($pd * 2)) % 11));

	// valida se é igual ao informado
	for($cn = 9; $cn <= 10; ++$cn) {
		if ((int)$cpf[9] !== $pd || (int)$cpf[10] !== $sd)
			return false;
	}
	
	return true;
}

function getOrder($order) {
	return ($order === 'asc') ? 'desc' : 'asc';
}

function getSystemWindows() {
	$SO = stristr(php_uname('s'), 'Windows');
	return (strpos($SO, 'Windows') !== false) ? true : false;
}

function getSystemLinux() {
	$SO = stristr(php_uname('s'), 'Linux');
	
	return (strpos($SO, 'Linux') !== false) ? true : false;
}

function doCreateUserFolder($cpf) {
	$cpf = md5($cpf);

	if (getSystemWindows()) 
		$dir = 'engine/users/images/';
	else 
		$dir = '/var/www/html/web/engine/users/images/';
	
	if (is_dir($dir.'/'.$cpf) === false)
		mkdir($dir.'/'.$cpf, 0777, true);
}

function getUserFolder($cpf) {
	$cpf = md5($cpf);
	
	if (getSystemWindows()) 
		$dir = 'engine/users/images';
	else 
		$dir = '/var/www/html/web/engine/users/images';
	
	$dirFiles = $dir.'/'.$cpf;
	return (is_dir($dirFiles)) ? $dirFiles : false;
}


function getUserFolderForIMG($cpf) {
	$cpf = md5($cpf);
	
	if (getSystemWindows()) 
		$dir = 'engine/users/images';
	else 
		$dir = '/web/engine/users/images';
	
	$dirFiles = $dir.'/'.$cpf;
	
	return $dirFiles;
}

function getSystemFolder() {
	
	if (getSystemWindows()) 
		$dir = 'web';
	else 
		$dir = '/var/www/html/web';
	
	return (is_dir($dir)) ? $dir : false;
}

// doListCollaborator();
// Cria array com todos os colaboradores cadastrado
function doListCollaborator($group) {
	$query = mysql_select_multi("SELECT `usuarios`.`id`, `usuarios`.`conta_id`, `usuarios`.`primeiro_nome`, `usuarios`.`ultimo_nome` FROM `usuarios` INNER JOIN `contas` ON `usuarios`.`id` = `contas`.`id` and `contas`.`nv_acesso` >= $group;");

	return ($query) ? $query : false;
}

// getSizeString($string, $min, $max);
// Valida se a string está entre o minimo e o máximo informado
function getSizeString($string, $min, $max) {
	$string = (string)$string;
	
	return (strlen($string) < $min || strlen($string) > $max) ? false : true;
}

// doLoginRedirect();
// Redireciona o jogador que está logon para a página myaccount
function doLoginRedirect() {
	if (getLoggedIn() === true) {
		header('Location: myaccount.php');
	}
}

// sanitizeString($str);
// Limpa a string de caractéres especiais
function sanitizeString($str) {
    $str = preg_replace('/[áàãâä]/ui', 'a', $str);
    $str = preg_replace('/[éèêë]/ui', 'e', $str);
    $str = preg_replace('/[íìîï]/ui', 'i', $str);
    $str = preg_replace('/[óòõôö]/ui', 'o', $str);
    $str = preg_replace('/[úùûü]/ui', 'u', $str);
    $str = preg_replace('/[ç]/ui', 'c', $str);
    $str = preg_replace('/[,.\/(),;:|!"#$%&=?~^><ªº-]/', '', $str);
//    $str = preg_replace('/[^a-z0-9]/i', '_', $str);
    $str = preg_replace('/_+/', '_', $str); // ideia do Bacco :)
    return $str;
}

// isSameCharacter($string);
// Verifica se todos os caracteres são iguais
function isSameCharacter($string) {
	$firstCharacter = $string[1];
	for($count = 0; $count < strlen($string); ++$count) {
		if($string[$count] !== $firstCharacter) 
			return false;
	}

	return true;
}

function doFormatCPF($cpf) {
 	$cpf = preg_replace("/[^0-9]/", "", $cpf);
	$qtd = strlen($cpf);

	if($qtd >= 11) {
		if($qtd === 11 ) {
			$cpf = 			substr($cpf, 0, 3) . '.' .
							substr($cpf, 3, 3) . '.' .
							substr($cpf, 6, 3) . '/' .
							substr($cpf, 9, 2);
		}

		return $cpf;

	}
}

// doSliceName($name, $type = 0);
// separa o primeiro nome do sobre nome, $type = true retorna o sobrenome
function doSliceName($name, $type = 0) {
    $name = sanitizeString($name);
    $explode = explode(' ', $name);
    $sobName = array();

    for($c = 1; $c < count($explode); ++$c) {
        $sobName[] = $explode[$c];
    }

    $name = implode(" ", $sobName);

    return ($type == 0) ? $explode[0] : $name;
}


// doDeleteFolder($dir);
// Deleta uma pasta e seus arquivos
function doDeleteFolder($dir) { 

	if(is_dir($dir)) {
		$files = array_diff(scandir($dir), array('.','..')); 

		foreach ($files as $file) { 
		  (is_dir("$dir/$file")) ? doDeleteFolder("$dir/$file") : unlink("$dir/$file"); 
		} 
		return rmdir($dir); 	
	}

	return false;
  }


// doInsertTableValues($table, $data); -- Insere os dados na tabela mais facilmente;
function doInsertTableValues($table, $data) {
	array_walk($data, 'array_sanitize');

	$fields = array_keys($data); // Fetch select fields
	$values = array_values($data); // Fetch insert data
	
	$fields_sql = implode("`, `", $fields); // Convert array into SQL compatible string
	$data_sql = implode("', '", $values); // Convert array into SQL compatible string

	mysql_insert("INSERT INTO $table (`$fields_sql`) VALUES ('$data_sql');");
}


/* ****************************
   **** FIM ****
   ****************************
*/


/* ****************************
   **** OUTRAS ****
   ****************************
*/

if(isset($_REQUEST['subpage']))
	$subpage = (string) $_REQUEST['subpage'];
else
	$subpage = '';

function setSession($key, $data) {
	global $sessionPrefix;
	$_SESSION['cooldownSession'] = time();
	$_SESSION[$sessionPrefix.$key] = $data;
}

function getSession($key) {
	global $sessionPrefix;
	getTimeSession();
	return (isset($_SESSION[$sessionPrefix.$key])) ? $_SESSION[$sessionPrefix.$key] : false;
}

function getTimeSession() {
	if (isset($_SESSION['cooldownSession']) && (time() - $_SESSION['cooldownSession'] > config('timeLogout'))) { 
		header('Location: index.php');
		session_destroy();
	}
}

function data_dump($print = false, $var = false, $title = false) {
	if ($title !== false) echo "<pre><font color='red' size='5'>$title</font><br>";
	else echo '<pre>';
	if ($print !== false) {
		echo 'Print: - ';
		print_r($print);
		echo "<br>";
	}
	if ($var !== false) {
		echo 'Var_dump: - ';
		var_dump($var);
	}
	echo '</pre><br>';
}

function url($path = false) {
	$folder   = dirname($_SERVER['SCRIPT_NAME']);
	return config('site_url') . '/' . $path;
}


// Fetch a config value. Etc config('vocations') will return vocation array from config.php.
function config($value) {
	global $config;
	return $config[$value];
}

// Some functions uses several configurations from config.php, so it sounds
// smarter to give them the whole array instead of calling the function all the time.
function fullConfig() {
	global $config;
	return $config;
}

function array_sanitize(&$item) {
	$item = htmlentities(strip_tags(doMysqlEscapeSstring($item)));
}

function sanitize($data) {
	return htmlentities(strip_tags(doMysqlEscapeSstring($data)));
}

function doConvertUpdateSQLFormat($data) {
	$sqlformat = "";
	foreach($data as $column => $data) {
		$sqlformat = $sqlformat."`".$column."`='".$data."'";
	}
	$sqlformat = str_replace("'`", "', `", $sqlformat);
	return $sqlformat;
}

function generateRandomString($length = 16) {
	$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567!@#$%&*';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

?>
