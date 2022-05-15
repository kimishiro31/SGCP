<?php

	$config['site_title'] = 'Podologia'; // Titulo do site
	$config['game_initials'] = 'PDG'; // Nome do servidor
	$config['site_description'] = 'Podolgoia'; // Descrição do servidor
	$config['site_url'] = 'localhost'; // URL do site para futuras verificações por email

    $config['sqlUser'] = 'root'; // USUARIO DO MYSQL
	$config['sqlPassword'] = '283817Ab@'; // SENHA DO MYSQL
	$config['sqlDatabase'] = 'podologiaDB2'; // NAME DA DATABASE DO MYSQL
	$config['sqlHost'] = 'localhost'; // HOST DO MYSQL


	// ------------------------ \\
	//      CONFIGURAÇÕES       \\
	// ------------------------ \\
	$config['userNameLenght'] = array(6, 32); // tamanho máximo que tem que ter o nome de usuário
	$config['passwordLenght'] = array(6, 100); // tamanho máximo que pode ter a senha 
	$config['timeLogout'] = 3600; // tempo para a sessão do jogador cair
	$config['logoEnabled'] = true;
	$config['path_images'] = 'web/engine/users/images';
	$config['business_hours'] = array(
		// Segunda-Feira
		'Monday' => array('starting' => '08:00', 'ending' => '18:00'),
		// Terça-Feira
		'Tuesday' => array('starting' => '08:00', 'ending' => '18:00'),
		// Quarta-Feira
		'Wednesday' => array('starting' => '08:00', 'ending' => '18:00'),
		// Quinta-Feira
		'Thursday' => array('starting' => '08:00', 'ending' => '18:00'),
		// Sexta-Feira
		'Friday' => array('starting' => '08:00', 'ending' => '18:00'),
		// Sabado
		'Saturday' => array('starting' => '08:00', 'ending' => '18:00'),
		// Domingo
		'Sunday' => array('starting' => '08:00', 'ending' => '18:00')
	);

	$config['rowsPerPage'] = 25;
	
	// NÍVEIS DE ACESSO
	$config['groups'] = array(
		0 => 'COMUM',
		1 => 'FUNCIONARIO',
		2 => 'GERENTE',
		3 => 'ADMINISTRADOR'
	);
	
	// TAMANHO MÁXIMO DA IMAGEM DO USUARIO
	$config['picture'] = array(
		'width' => 1000,
		'height' => 1000
	);

	// ------------------------ \\
	//         SEGURANÇA        \\
	// ------------------------ \\
	// OBS: SE NÃO SOUBER GERAR AS CHAVES ACESSE O LINK -> https://cloud.google.com/recaptcha-enterprise/docs/create-key?hl=pt-br
	$config['googleRECAPTCHA'] = array(
		'enabled' => false, // ativa ou desativa o sistema
		'site_key' => '6LcvG_4bAAAAAFyfOCpsz8eY9XKGfF9tIhHWxb1y', // chave do site que foi gerada pelo google
		'secret_key' => '6LcvG_4bAAAAAOGz8lELLgicgBO-eRL80aHv1oZO', // chave secreta que foi gerada pelo google
	);
?>