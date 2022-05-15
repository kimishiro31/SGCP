<?php

/* getSchedulingData($schedule_ID, campo1, campo2...);
// captura as colunas da tabela Account e joga num array
	MODO DE USO
	$account = getPathologyData(schedule_ID, 'password', 'email');
	echo $account['email']; ele vai retorna com o email
*/
function getSchedulingData($schedule_ID) {
	$data = array();
	$schedule_ID = sanitize($schedule_ID);
	
	$func_num_args = func_num_args();
	$func_get_args = func_get_args();
	
	if ($func_num_args > 1)  {
		unset($func_get_args[0]);
		
		$fields = '`'. implode('`, `', $func_get_args) .'`';
		return mysql_select_single("SELECT $fields FROM `agendamentos` WHERE `id` = '".$schedule_ID."' LIMIT 1;");
	} else 
	return false;
}

/*
isSchedulingExist($schedule_ID)
Descrição: Verifica se existe o agendamento, retorna true ou false
*/
function isSchedulingExist($schedule_ID) {
	$query = mysql_select_single("SELECT `id` FROM `agendamentos` WHERE `id`='$schedule_ID';");

	return ($query) ? true : false;
}



/*
getSchedulingUserID($schedule_ID)
Descrição: Retorna o usuario ID do agendamento
*/
function getSchedulingUserID($schedule_ID) {
	$data = getSchedulingData($schedule_ID, 'usuario_id');

	return ($data !== false) ? $data['usuario_id'] : false;
}


/*
getSchedulingCollaboratorID($schedule_ID)
Descrição: Retorna o colaborador ID do agendamento
*/
function getSchedulingCollaboratorID($schedule_ID) {
	$data = getSchedulingData($schedule_ID, 'collaborator_id');

	return ($data !== false) ? $data['collaborator_id'] : false;
}

/*
getSchedulingSchedulerID($schedule_ID)
Descrição: Retorna quem criou o agendamento
*/
function getSchedulingSchedulerID($schedule_ID) {
	$data = getSchedulingData($schedule_ID, 'agendador_id');

	return ($data !== false) ? $data['agendador_id'] : false;
}

/*
getSchedulingDateCreated($schedule_ID)
Descrição: Retorna a data que foi criado o agendamento
*/
function getSchedulingDateCreated($schedule_ID) {
	$data = getSchedulingData($schedule_ID, 'data_agendamento');

	return ($data !== false) ? date("d/m/Y - H:i", $data['data_agendamento']) : false;
}

/*
getSchedulingDateScheduled($schedule_ID)
Descrição: Retorna a data agendada do agendamento
*/
function getSchedulingDateScheduled($schedule_ID) {
	$data = getSchedulingData($schedule_ID, 'data_agendada');

	return ($data !== false) ? doDateConvert($data['data_agendada']) : false;
}

/*
getSchedulingTimeScheduled($schedule_ID)
Descrição: Retorna a hora agendada do agendamento
*/
function getSchedulingTimeScheduled($schedule_ID) {
	$data = getSchedulingData($schedule_ID, 'hora_agendada');

	return ($data !== false) ? $data['hora_agendada'] : false;
}

/*
getSchedulingDescription($schedule_ID)
Descrição: Retorna a descrição do agendamento
*/
function getSchedulingDescription($schedule_ID) {
	$data = getSchedulingData($schedule_ID, 'observacao');

	return ($data !== false) ? $data['observacao'] : false;
}


/*
getSchedulingStatus($schedule_ID)
Descrição: Retorna o status do agendamento
*/
function getSchedulingStatus($schedule_ID) {
	$data = getSchedulingData($schedule_ID, 'status');

	return ($data !== false) ? $data['status'] : false;
}

/*
isSchedulingServicePerformed($schedule_ID)
Descrição: Verifica se o atendimento foi confirmado e retorna True ou False
*/
function isSchedulingServicePerformed($schedule_ID) {
	$status = getSchedulingStatus($schedule_ID);

	return ($status !== false && $status == 1) ? true : false;
}

/*
doCreateScheduling($register_data)
Descrição: Faz um novo agendamento
*/
function doCreateScheduling($register_data) {
	array_walk($register_data, 'array_sanitize');
	
	

	$schedule_import = array(
		'USUARIO_ID' => $register_data['USUARIO_ID'],
		'AGENDADOR_ID' => $register_data['AGENDADOR_ID'],
		'DATA_AGENDAMENTO' => $register_data['DATA_AGENDAMENTO'],
		'DATA_AGENDADA' => $register_data['DATA_AGENDADA'],
		'HORA_AGENDADA' => $register_data['HORA_AGENDADA'],
		'COLLABORATOR_ID' => $register_data['COLLABORATOR'],
	);

	
	$service_schedule_import = array(
		$register_data['SERVICE1'],
		$register_data['SERVICE2'],
		$register_data['SERVICE3'],
		$register_data['SERVICE4']
	);
	
	foreach($service_schedule_import as $key => $value) {
		if($value == 0) 
			unset($service_schedule_import[$key]);
		
	}

	
	$service_schedule_import = array_combine(range(1, count($service_schedule_import)), array_values($service_schedule_import));

	mysql_insert("INSERT INTO `agendamentos` (`usuario_id`, `agendador_id`,`collaborator_id`, `data_agendamento`, `data_agendada`, `hora_agendada`) VALUES ('".$schedule_import['USUARIO_ID']."', '".$schedule_import['AGENDADOR_ID']."', '".$schedule_import['COLLABORATOR_ID']."', '".$schedule_import['DATA_AGENDAMENTO']."', '".$schedule_import['DATA_AGENDADA']."', '".$schedule_import['HORA_AGENDADA']."')");
	$lastInsert = mysql_getlast_insert();

	foreach($service_schedule_import as $key => $value) 
		mysql_insert("INSERT INTO `servicos_agendado` (`agendamento_id`, `servico_id`) VALUES ('".$lastInsert."', '".$value."')");
	

}


/*
getScheduledServices($id)
Descrição: retorna a lista de todos os serviços agendado para o $id agendamento
*/
function getScheduledServices($id) {
	$id = sanitize($id);
//, `servicos`.`tempo`, `servicos`.`valor`, `servicos`.`descricao`
	$query = mysql_select_multi("SELECT `servicos`.`id` FROM `agendamentos`
	INNER JOIN `servicos_agendado` ON `servicos_agendado`.`agendamento_id` = `agendamentos`.`id` AND `agendamentos`.`id`=$id
	INNER JOIN `servicos` ON `servicos_agendado`.`SERVICO_ID` = `servicos`.`id`;");

	return ($query !== false) ? $query : false;

}

/*
getFreeScheduling($date, $hours)
Descrição: Confirma se horário está livre
*/
function getFreeScheduling($date, $hours) {
	$query = mysql_select_single("SELECT `id` FROM `agendamentos` WHERE `data_agendada`='".$date."' and `hora_agendada`='".$hours."'");

	return ($query !== false) ? true : false;
}


/*
doConfirmService($register_data)
Descrição: confirma que o atendimento foi realizado
*/
function doConfirmService($register_data) {
	$image = $register_data['image'];
	unset($register_data['image']);
	array_walk($register_data, 'array_sanitize');
	$id = $register_data['schedule_id'];
	$query = mysql_select_single("SELECT `id`, `usuario_id` FROM `agendamentos` WHERE `id` = '".$id."';");
	$cpf = getUserCPF($query['usuario_id']);
	$obs = $register_data['observacao'];

	if($query !== false) {
		mysql_update("UPDATE `agendamentos` SET `status` = '1' WHERE `id` = '".$id."';");

		$confirm = mysql_select_single("SELECT `id` FROM `atendimentos_realizado` WHERE `agendamento_id` = '".$id."';");
		$confirmPay = mysql_select_single("SELECT `id` FROM `pagamentos` WHERE `agendamento_id` = '".$id."';");
		
		if($confirm !== false) {
			mysql_update("UPDATE `atendimentos_realizado` SET `data_confirmacao` = '".TIME()."', `observacao` = '".$obs."' WHERE `id` = '".$confirm['id']."';");

			
			if($image !== false) {
				$countfiles = count($image['name']);
				for ($i=0; $i < $countfiles; $i++) {
					$image_data = array(
						'name' => $image['name'][$i], 
						'tmp' => $image['tmp_name'][$i], 
						'size' => $image['size'][$i], 
						'type' => $image['type'][$i],
						'new_name' => md5(generateRandomString(3)).'_'.date('Y').'_'.date('m').'_'.date('d').'.'.pathinfo($image['name'][$i], PATHINFO_EXTENSION), 
					);
	
					if($image_data['size'] > 0 ) {
						if (is_dir('engine/users/images/'.md5($cpf).'/'.$id) === false) 
							mkdir('engine/users/images/'.md5($cpf).'/'.$id, 0777, true);
								move_uploaded_file($image_data["tmp"], getUserFolder($cpf).'/'.$id.'/'.$image_data["new_name"]);
								mysql_insert("INSERT INTO `atendimento_image` (`atendimento_id`, `img`) VALUES('".$confirm['id']."', '".$image_data["new_name"]."')");
					}
				}
			}

			if($confirmPay === FALSE) {
				mysql_insert("INSERT INTO `pagamentos` (`agendamento_id`, `valor_total`, `tipo_pagamento`, `data_pagamento`, `hora_pagamento`, `caixa_id`) VALUES('".$id."', '".$register_data['valor']."', '".$register_data['pay_type']."', '".date("Y-m-d")."', '".date("H:i:s")."', ".getCashierOpeningID().")");
			} else {
				mysql_update("UPDATE `pagamentos` SET `hora_pagamento` = '".date("H:i:s")."',`data_pagamento` = '".date("Y-m-d")."', `tipo_pagamento` = '".$register_data['pay_type']."', `valor_total`='".$register_data['valor']."' WHERE `id` = '".$confirmPay['id']."';");
			}

		}	else {
			mysql_insert("INSERT INTO `atendimentos_realizado` (`agendamento_id`, `data_confirmacao`, `observacao`) VALUES('".$query['id']."', '".TIME()."', '".$obs."')");
			$last_id = mysql_getlast_insert();
			if($image !== false) {
				$countfiles = count($image['name']);
				for ($i=0; $i < $countfiles; $i++) {
					$image_data = array(
						'name' => $image['name'][$i], 
						'tmp' => $image['tmp_name'][$i], 
						'size' => $image['size'][$i], 
						'type' => $image['type'][$i],
						'new_name' => md5(generateRandomString(3)).'_'.date('Y').'_'.date('m').'_'.date('d').'.'.pathinfo($image['name'][$i], PATHINFO_EXTENSION), 
					);
	
					if($image_data['size'] > 0 ) {
						if (is_dir('engine/users/images/'.md5($cpf).'/'.$id) === false) 
							mkdir('engine/users/images/'.md5($cpf).'/'.$id, 0777, true);
								move_uploaded_file($image_data["tmp"], 'engine/users/images/'.md5($cpf).'/'.$id.'/'.$image_data["new_name"]);
								mysql_insert("INSERT INTO `atendimento_image` (`atendimento_id`, `img`) VALUES('".$last_id."', '".$image_data["new_name"]."')");
					}
				}
			}	
			
			if($confirmPay === FALSE) {
				mysql_insert("INSERT INTO `pagamentos` (`agendamento_id`, `valor_total`, `tipo_pagamento`, `data_pagamento`, `hora_pagamento`, `caixa_id`) VALUES('".$id."', '".$register_data['valor']."', '".$register_data['pay_type']."', '".date("Y-m-d")."', '".date("H:i:s")."', ".getCashierOpeningID().")");
			} else {
				mysql_update("UPDATE `pagamentos` SET `hora_pagamento` = '".date("H:i:s")."', `data_pagamento` = '".date("Y-m-d")."', `tipo_pagamento` = '".$register_data['pay_type']."', `valor_total`='".$register_data['valor']."' WHERE `id` = '".$confirmPay['id']."';");
			}
		}
	}	else 
		return false;
}


/*
doDeleteScheduling($id)
Descrição: deleta o agendamento e tudo relacionado a ele
*/
function doDeleteScheduling($id) {
	
	$sch_rl = mysql_select_single("SELECT `id`, `usuario_id` FROM `agendamentos` WHERE `id` = '".$id."';");
	$atd_rl = mysql_select_single("SELECT `id` FROM `atendimentos_realizado` WHERE `agendamento_id` = '".$id."';");
	$img_rl = mysql_select_single("SELECT `id` FROM `atendimento_image` WHERE `atendimento_id` = '".$atd_rl['id']."';");
	$pay_rl = mysql_select_single("SELECT `id` FROM `pagamentos` WHERE `agendamento_id` = '".$id."';");
	$ssc_rl = mysql_select_single("SELECT `id` FROM `servicos_agendado` WHERE `agendamento_id` = '".$id."';");

	if($sch_rl !== false)
		mysql_delete("DELETE FROM `agendamentos` WHERE `id`='".$sch_rl['id']."';");
		if($atd_rl !== false)
			mysql_delete("DELETE FROM `atendimentos_realizado` WHERE `id`='".$atd_rl['id']."';");
			if($pay_rl !== false)
				mysql_delete("DELETE FROM `pagamentos` WHERE `id`='".$pay_rl['id']."';");
				if($img_rl !== false)
					mysql_delete("DELETE FROM `atendimento_image` WHERE `id`='".$img_rl['id']."';");
					if($ssc_rl !== false)
						mysql_delete("DELETE FROM `servicos_agendado` WHERE `id`='".$ssc_rl['id']."';");
}

/*
doRestartScheduling($id)
Descrição: Reinicia o status do agendamento
*/
function doRestartScheduling($id) {
	$id = sanitize($id);
	mysql_update("UPDATE `agendamentos` SET `status` = '0' WHERE `id` = '".$id."';");
}


/*
getFinishedTreatment($id, $increment = false)
Descrição: Calcula o tempo total do atendimento
*/
function getTimeTotalService($id, $increment = false) {
	$id = sanitize($id);

	$query = mysql_select_single("SELECT time_format(SEC_TO_TIME(SUM( TIME_TO_SEC(`servicos`.`tempo`))),'%H:%i:%s') AS TEMPO_TOTAL FROM `agendamentos`
		INNER JOIN `servicos_agendado` ON `servicos_agendado`.`agendamento_id` = `agendamentos`.`id` AND `agendamentos`.`id`='".$id."'
		INNER JOIN `servicos` ON `servicos_agendado`.`servico_id` = `servicos`.`id`;");

	if($query) {
        $explodeHours = explode(':', $query['TEMPO_TOTAL']);

		if($increment === true && count($explodeHours) >= 2)
			return '+'.$explodeHours[0].' hours +'.$explodeHours[1].' minutes +'.$explodeHours[2].' seconds';
		else
			return $query['TEMPO_TOTAL'];
	}
}


/*
getValueTotalService($id)
Descrição: Calcula o valor total do atendimento
*/
function getValueTotalService($id) {
	$id = sanitize($id);

	$query = mysql_select_single("SELECT SUM(`servicos`.`valor`) AS VALOR_TOTAL FROM `agendamentos`
	INNER JOIN `servicos_agendado` ON `servicos_agendado`.`agendamento_id` = `agendamentos`.`id` AND `agendamentos`.`id`=$id
	INNER JOIN `servicos` ON `servicos_agendado`.`servico_id` = `servicos`.`id`;");

	return ($query !== false) ? $query['VALOR_TOTAL'] : false;
}

/*
doListHoursAvailable($date)
Descrição: Retorna todos os horários livre da data
*/
function doListHoursAvailable($date) {
	$hours = array();
    $dateSelect = date('l', strtotime($date));

    $start = doTimeConvert(config('business_hours')[$dateSelect]['starting']);
    $ending = doTimeConvert(config('business_hours')[$dateSelect]['ending']);

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

	return (!empty($hours)) ? $hours : false;
}


/*
doListHoursScheduled($date)
Descrição: Retorna todos os horários marcado da data
*/
function doListHoursScheduled($date, $answered = false) {
	if ($answered !== false) 
		$query = mysql_select_multi("select * from `agendamentos` where `data_agendada`='".$date."' and `status`=1 order by `hora_agendada` asc;");
	else
	    $query = mysql_select_multi("select * from `agendamentos` where `data_agendada`='".$date."' and `status`=0 order by `hora_agendada` asc;");
	
	return ($query !== false) ? $query : false;
}


/*
getTotalScheduling()
Descrição: Retorna um total de agendamentos incluindo os já atendidos
*/
function getTotalScheduling() {
	$query = mysql_select_single("SELECT COUNT(*) as `total_agendamentos` FROM `agendamentos`;");
	
	return str_pad($query['total_agendamentos'] , 2, '0' , STR_PAD_LEFT);;
}

































?>