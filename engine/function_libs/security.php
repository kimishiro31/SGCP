<?php


function something() {
	// Make acc data compatible:
	$ip = getIPLong();
}

// getToken($token);
// Verifica se o token do input é o mesmo que a sessão($token) iniciada
function getToken($token) {
	return ((isset($_SESSION[$token]) && isset($_POST['token'])) && ($_SESSION[$token] == $_POST['token'])) ? true : false;
}

// addToken($token);
// retorna a sessão do token $token
function addToken($token) {
	return $_SESSION[$token];
}

// setToken($token);
// inicia a sessão do $token
function setToken($token) {
	$_SESSION[$token] = generateRandomString(16);
}

// destroyToken($token);
// destroi a sessão do $token
function destroyToken($token) {
	unset($_SESSION[$token]);
}


/* doProtect();
Descrição: Faz a proteção da página, se o usuario não estiver logado ele redireciona para a página de proteção.
*/
function doProtect() {
	if (getLoggedIn() === false) {
		header('Location: index.php');
		exit();
	}
}


/* getPageAccess($user_ID, $group);
Descrição: Faz a proteção da página, se o usuario não estiver logado ele redireciona para a página de proteção.
*/
function getPageAccess($user_ID, $group) {
	doProtect();

	if (getGroup($user_ID) < $group) {
		header('Location: index.php');
		exit();
	}
}



// Checks if an IPv4(or localhost IPv6) address is valid
function validate_ip($ip) {
	$ipL = safeIp2Long($ip);
	$ipR = long2ip($ipL);

	if ($ip === $ipR) {
		return true;
	} elseif ($ip=='::1')  {
		return true;
	} else {
		return false;
	}
}



// Gets you the actual IP address even from users behind ISP proxies and so on.
function getIP() {

	$IP = '';
	if (getenv('HTTP_CLIENT_IP')) {
	  $IP =getenv('HTTP_CLIENT_IP');
	} elseif (getenv('HTTP_X_FORWARDED_FOR')) {
	  $IP =getenv('HTTP_X_FORWARDED_FOR');
	} elseif (getenv('HTTP_X_FORWARDED')) {
	  $IP =getenv('HTTP_X_FORWARDED');
	} elseif (getenv('HTTP_FORWARDED_FOR')) {
	  $IP =getenv('HTTP_FORWARDED_FOR');
	} elseif (getenv('HTTP_FORWARDED')) {
	  $IP = getenv('HTTP_FORWARDED');
	} else {
		// VERIFICA SE CLOUNDFLARE ESTÁ ATIVO
	  $IP = (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR'];
	} 
  return $IP;
  }
  
  function safeIp2Long($ip) {
	  return sprintf('%u', ip2long($ip));
  }
  
  // Gets you the actual IP address even from users in long type
  function getIPLong() {
	  return safeIp2Long(getIP());
  }
  

  
// setRECaptcha();
// Adiciona um recpacha no formulario OBS: Coloque acima do botão de submit;
function setRECaptcha() {
	$googleRECAPTCHA = config('googleRECAPTCHA');
	$site_key = $googleRECAPTCHA['site_key'];
	$status = $googleRECAPTCHA['enabled'];
	
	if ($status) {
		echo '<script src="https://www.google.com/recaptcha/api.js"></script>';
		return '
				<div style="position: absolute;left: 475px;top: 0px;" class="g-recaptcha" data-sitekey="'.$site_key.'"></div>
				';
	}
}

// getRECaptcha();
// Verifica se o usuário preencheu o captcha OBS: Coloque em validações
function getRECaptcha() {
	$googleRECAPTCHA = config('googleRECAPTCHA');
	$secret_key = $googleRECAPTCHA['secret_key'];
	$status = $googleRECAPTCHA['enabled'];
	
	if ($status) {
		$response = null;
		$reCaptcha = new ReCaptcha($secret_key);

		if ($_POST['g-recaptcha-response'])
			$response = $reCaptcha->verifyResponse(getIP(), $_POST['g-recaptcha-response']);

		if ($response != null || $response->success)
			return true;
	}
	
	return ($status) ? false : true;	
}

?>