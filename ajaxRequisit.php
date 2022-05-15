<?php 
require_once 'engine/init.php';

if (($subpage == 'register') && getToken('registerUser')) {
	
	/*********************************************************
	**********************************************************
	**
	**	PAGINA DE VALIDAÇÃO DE CADASTRO
	**
	**
	**
	***********************************************************
	**********************************************************/

	// Faz as validações e verifica se tem erros
	if (empty($_POST) === false) {
		$firstName = strtoupper(sanitizeString($_POST['first_name']));
		$lastName = strtoupper(sanitizeString($_POST['last_name']));
		$birthDate = $_POST['birthDate'];
		$gender = strtoupper($_POST['gender']);
		$cpf = sanitizeString($_POST['cpf']);
		$rg = sanitizeString($_POST['rg']);
		$phoneP = str_replace(' ', '', (sanitizeString($_POST['phoneP'])));
		$phoneS = str_replace(' ', '', (sanitizeString($_POST['phoneS'])));
		$email = $_POST['email'];
		$ad_cep = sanitizeString($_POST['address_cep']);
		$ad_num = $_POST['address_num'];
		$ad_complemento = strtoupper(sanitizeString($_POST['address_comp']));
		$ad_rua = strtoupper(sanitizeString($_POST['address_rua']));
		$ad_bairro = strtoupper(sanitizeString($_POST['address_bairro']));
		$ad_cidade = strtoupper(sanitizeString($_POST['address_cidade']));
		$ad_estado = strtoupper(sanitizeString($_POST['address_estado']));
		$nationality = (empty($_POST['nationality'])) ? NULL : strtoupper(sanitizeString($_POST['nationality']));
		$profission = (empty($_POST['profission'])) ? NULL : strtoupper(sanitizeString($_POST['profission']));

        $required_fields = array('firstName', 'lastName', 'birthDate', 'gender', 'cpf', 'rg', 'phoneP', 'ad_cep', 'ad_num', 'ad_rua', 'ad_bairro', 'ad_cidade', 'ad_estado');

        foreach($_POST as $key=>$value) {
            if (empty($value) && in_array($key, $required_fields) === true) {
                $errors[] = "É obrigatório o preenchimento de todos os campos com o (*).";
                break 1;
            }
        }

        /* VALIDAÇÕES DADOS PESSOAIS - PRIMEIRO NOME */
		if (preg_match("/^[a-zA-Z ]+$/", $firstName) === false) {
			$errors[] = "No campo primeiro nome, somente é aceito caracteres alfabetico e sem acentuação.";
		} 
        elseif (strlen($firstName) > 20) {
            $errors[] = "O primeiro nome está muito longo.";
        }
        
        /* VALIDAÇÕES DADOS PESSOAIS - SEGUNDO NOME */
        if (preg_match("/^[a-zA-Z ]+$/", $lastName) === false) {
			$errors[] = "No campo sobrenome, somente é aceito caracteres alfabetico e sem acentuação.";
		} 
        elseif (strlen($lastName) > 80) {
            $errors[] = "O sobrenome está muito longo.";
        }

        /* VALIDAÇÕES DADOS PESSOAIS - DATA DE NASCIMENTO */
		
		if((int)date("d", strtotime($birthDate)) < 1 || (int)date("d", strtotime($birthDate)) > getDaysInMonth((int)date("m", strtotime($birthDate)), (int)date("Y", strtotime($birthDate)))) {
			$errors[] = "O dia escolhido é inexistente no calêndario.";
		}
        elseif ((int)date("m", strtotime($birthDate)) < 1 || (int)date("m", strtotime($birthDate)) > 12) {
            $errors[] = "Mês de nascimento escolhido não existe no calendario.";
        }

		if (is_numeric((int)date("Y", strtotime($birthDate))) === false) {
			$errors[] = "No ano do seu nascimento só é aceito numero de ".(date("Y")-100)."-".date("Y").".";
		}
        elseif ((int)date("Y", strtotime($birthDate)) < (date("Y")-100) || (int)date("Y", strtotime($birthDate)) > date("Y")) {
            $errors[] = "Mês de nascimento escolhido não existe no calendario.";
        }
        
        /* VALIDAÇÕES DADOS PESSOAIS - GÊNERO */
        
		if ($gender !== 'M' && $gender !== 'F') {
			$errors[] = "Favor verificar o gênero, o mesmo está errado.";
		}
		if (doCPFValidation($cpf) === false) {
			$errors[] = "Favor verificar o cpf, o mesmo está invalido.";
		}

        /* VALIDAÇÕES DADOS PESSOAIS - TELEFONE */
		if (is_numeric($phoneP) === false) {
			$errors[] = "Numero de telefone só pode ter numeros.";
		}
		if (!empty($phoneS) && is_numeric($phoneS) === false) {
			$errors[] = "Numero de telefone só pode ter numeros.";
		}

        /* VALIDAÇÕES ENDEREÇO - CEP */
		if (is_numeric($ad_cep) === false) {
			$errors[] = "No CEP somente é aceito numeração.";
		}
        elseif (strlen($ad_cep) < 1 || strlen($ad_cep) > 8) {
            $errors[] = "CEP tem que conter no minímo de 8 caracteres.";
        }
        elseif (isSameCharacter($ad_cep)) {
            $errors[] = "CEP invalido.";
        }

        /* VALIDA SE O USUARIO JA EXISTE */
		if (getUserID($cpf) !== false || getUserID($rg) !== false) {
			$errors[] = "Esse usuário já se encontra cadastrado em nosso banco de dados.";
		}
    }


    // Se as validações passaram sem erros entra aqui
	if (empty($_POST) === false && empty($errors) === true) {
		$register_data = array(
            /* CONTA */
			'username'		=>	$cpf,
			'password'	    =>	$cpf,
			'phoneP'		=>	$phoneP,
			'phoneS'		=>	$phoneS,
			'email'		=>	$email,
            'created'	=>	date("Y-m-d"),
			'ip'		=>	getIPLong(),

            /* USUARIO */
			'firstName'		=>	$firstName,
			'lastName'		=>	$lastName,
			'bithDate'		=>	$birthDate,
			'cpf'		    =>	$cpf,
			'rg'		    =>	$rg,
			'gender'		=>	$gender,
			'ad_cep'		=>	$ad_cep,
			'ad_rua'		=>	$ad_rua,
			'ad_bairro'		=>	$ad_bairro,
			'ad_num'		=>	$ad_num,
			'ad_comp'		=>	$ad_complemento,
			'ad_cid'		=>	$ad_cidade,
			'ad_estado'		=>	$ad_estado,
			'nacionality'	=>	$nationality,
			'profission'	=>	$profission
		);

	    doCreateUser($register_data);
        $response = output_msgs("O cadastro foi efetuado com sucesso, você será redirecionado para a lista de usuários agora.", "usersList.php");
	}
	
	// se as validações passaram com erros entra aqui
	if (empty($errors) === false) {
		$response = output_errors($errors);
	}
	
	echo json_encode($response);
}

elseif ($subpage == 'days' && getToken('tokenScheduling') == $_POST['token']) {
    $resultYears = doDateList();
    $resultMonths = $resultYears[$_POST['year']];
    $resultDays = $resultMonths[$_POST['month']];
    
    echo json_encode((object)$resultDays);    
}
elseif ($subpage == 'hours' && getToken('tokenScheduling') == $_POST['token']) {
    $hours = array();
    $date = $_POST['dateSchedule'];
    $dateSelect = date('l', strtotime($date));

    $start = doTimeConvert($config['business_hours'][$dateSelect]['starting']);
    $ending = doTimeConvert($config['business_hours'][$dateSelect]['ending']);

    while($start <= $ending) {

        $query = mysql_select_single("SELECT time_format(SEC_TO_TIME(SUM( TIME_TO_SEC(`servicos`.`tempo`))),'%H:%i:%s') AS `TEMPO_TOTAL`,
                                        `agendamentos`.`usuario_id`, 
                                        `agendamentos`.`data_agendada`, 
                                        `agendamentos`.`hora_agendada` FROM `agendamentos`
                                            INNER JOIN `servicos_agendado` ON `servicos_agendado`.`agendamento_id` = `agendamentos`.`id` AND `agendamentos`.`data_agendada` = '".$date."' and `agendamentos`.`hora_agendada` = '".$start."'
                                            INNER JOIN `servicos` ON `servicos_agendado`.`servico_id` = `servicos`.`id`;");


        $explodeHours = explode(':', $query['TEMPO_TOTAL']);

        if($query !== false && count($explodeHours) >= 2) {
            $ConvertSuccessHours = '+'.$explodeHours[0].' hours +'.$explodeHours[1].' minutes +'.$explodeHours[2].' seconds';
            $start = doTimeConvert($start, $ConvertSuccessHours);
        } else {
            $hours[] = doTimeConvert($start);
            $start = doTimeConvert($start, "+15 minutes");             
        }     

    }

    echo json_encode((object)$hours);    
}
else {
    header('Location: myaccount.php');
}



?>