<?php 
require_once 'engine/init.php'; 
	
	if (isset($_SESSION)) {
		session_destroy();
		header('Location: index.php');
	} 
?>