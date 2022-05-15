<?php 
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
ini_set('memory_limit', '-1');

$l_time = microtime();
$l_time = explode(' ', $l_time);
$l_time = $l_time[1] + $l_time[0];
$l_start = $l_time;

function elapsedTime($l_start = false, $l_time = false) {
	if ($l_start === false) global $l_start;
	if ($l_time === false) global $l_time;

	$l_time = explode(' ', microtime());
	$l_finish = $l_time[1] + $l_time[0];
	return round(($l_finish - $l_start), 4);
}

$time = time();
$aacQueries = 0;
$accQueriesData = array();
$url_atual = $_SERVER["REQUEST_URI"];

session_start();
ob_start();
require_once 'config.php';

/*************************
**
** BIBLIOTECA DE FUNÇÕES
**
**************************/
require_once 'function_libs/connect.php';
require_once 'function_libs/general.php';
require_once 'function_libs/cashdesk.php';
require_once 'function_libs/account.php';
require_once 'function_libs/datetime.php';
require_once 'function_libs/outputs.php';
require_once 'function_libs/pathology.php';
require_once 'function_libs/questionnaire.php';
require_once 'function_libs/scheduling.php';
require_once 'function_libs/services.php';
require_once 'function_libs/group.php';
require_once 'function_libs/security.php';
require_once 'function_libs/users.php';
require_once 'function_libs/pays.php';
require_once 'function_libs/dashboard.php';
require_once 'function_libs/attendance.php';
require_once 'lib/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
//require_once 'lib/vendor/autoload.php';
header('Access-Control-Allow-Origin: *');

if (getLoggedIn() === true) {
	$session_user_id = getSession('user_id');
	$user_data = getAccountData($session_user_id, 'id', 'usuario', 'senha', 'email');
	$session_user_data = getAccountData($session_user_id, 'id', 'usuario', 'senha', 'email');
}

$errors = array();

$filename = explode('/', $_SERVER['PHP_SELF']);
$filename = $filename[count($filename)-1];
$page_filename = str_replace('.php', '', $filename);

?>
