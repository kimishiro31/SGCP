<?php
	require_once 'engine/init.php'; 
	doLoginRedirect();
	if($subpage == 'validation' && getToken('tokenLogin')) {
		$username = $_POST['username'];
		$password = $_POST['password'];

			if (empty($username) || empty($password)) {
				$errors[] = "Obrigatório o preenchimento de todos os campos.";
			} 
			else if (strlen($username) > $config['userNameLenght'][1] || strlen($password) > $config['passwordLenght'][1]) {
				$errors[] = "Máximo de caracteres permitido no campo usuário é de ".$config['userNameLenght'][1]." e no campo de senha é de ".$config['passwordLenght'][1].".";
			} 
			else if (isAccountLoginExist($username) === false) {
				$errors[] = "Usuário digitado é invalido.";
			} 
			else if (getRECaptcha() === false) {
				$errors[] = "Preencha o desáfio.";
			}
			else {
				// Começa o login
				$login = getLoginValidation($username, $password);
				if ($login === false) {
					$errors[] = "Usuário ou senha invalido.";
				}
				elseif (isBlockedAccount($login) === true) {
					$errors[] = "Sua conta está temporariamente suspensa, entre em contato com um funcionário para o desbloqueio da mesma.";
				}
				else {
					setSession('user_id', $login);
					destroyToken('tokenLogin');
					header('Location: myaccount.php');
					exit();
				}
			}
	}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>LOGIN</title>
		<meta name="keywords" content="podologia" />
		<link rel="icon" href="layout/icon.ico">
		
		<!-- Stylesheet(s) -->
		<link rel="stylesheet" type="text/css" href="/layout/css/homepage.css" />
		<link rel="stylesheet" type="text/css" href="/layout/css/inputs.css" />
		<link rel="stylesheet" type="text/css" href="/layout/css/animations.css" />
	</head>

	<body>
		<?php
		if (empty($errors) === false) {
			echo output_errors($errors);
		}
		?>
		<div id="lg-Container">
			<div id="lg-Content">
				<div id="lg-header">
					<?php if($config['logoEnabled']) { ?>
						<div id="logo-Frame">
							<img src="/layout/logo.jpg"/>
						</div>
					<?php } ?>
				</div>
					<br/><br/>
				<form action="index.php?subpage=validation" method="POST">
					<input type="text" name="username" class="mediumInputTxt" placeholder="USUÁRIO"/>
						<br/><br/>
					<input type="password" name="password" class="mediumInputTxt" placeholder="SENHA"/>
						<br/><br/>
                    <?php setToken('tokenLogin') ?>
                    <input name="token" type="text" value="<?php echo addToken('tokenLogin') ?>" hidden/>
					<button type="submit" class="enterButton">Entrar</button>
				</form>
			</div>
		</div>

	</body>
	
</html>