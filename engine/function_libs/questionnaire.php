<?php

/******************************************** */

		/*** QUESTIONARIO ***\ 

/******************************************** */


/* getQuestionnaireData($quest_ID, campo1, campo2...);
// captura as colunas da tabela Account e joga num array
	MODO DE USO
	$account = getQuestionnaireData(quest_ID, 'password', 'email');
	echo $account['email']; ele vai retorna com o email
*/
function getQuestionnaireData($quest_ID) {
	$data = array();
	$quest_ID = sanitize($quest_ID);
	
	$func_num_args = func_num_args();
	$func_get_args = func_get_args();
	
	if ($func_num_args > 1)  {
		unset($func_get_args[0]);
		
		$fields = '`'. implode('`, `', $func_get_args) .'`';
		return mysql_select_single("SELECT $fields FROM `questionarios` WHERE `id` = '".$quest_ID."' LIMIT 1;");
	} else 
	return false;
}


/*
isQuestionnaireExist($quest_ID)
Descrição: Verifica se existe o questionario, retorna true ou false
*/
function isQuestionnaireExist($quest_ID) {
	$query = mysql_select_single("SELECT `id` FROM `questionarios` WHERE `id`='$quest_ID';");

	return ($query) ? true : false;
}


/*
getQuestionnaireTitle($quest_ID);
Descrição: Retorna o titulo do questionario
*/
function getQuestionnaireTitle($quest_ID) {
	$data = getQuestionnaireData($quest_ID, 'title');

	return ($data) ? $data['title'] : false;
}


/*
getQuestionnaireDateCreated($quest_ID);
Descrição: Retorna a data que foi criado o questionario
*/
function getQuestionnaireDateCreated($quest_ID) {
	$data = getQuestionnaireData($quest_ID, 'title');

	return ($data) ? $data['title'] : false;
}


/*
doAddQuestionnaire($title);
Descrição: Adiciona um novo questionario
*/

function doAddQuestionnaire($title) {
	$title = sanitize($title);
    return mysql_insert("INSERT INTO `questionarios` (`title`, `data_criacao`) VALUES ('".$title."', ".date("Y-m-d").");");
}


/*
doRemoveQuestionnaire($id);
Descrição: Remove um questionario
*/

function doRemoveQuestionnaire($quest_ID) {
	$quest_ID = sanitize($quest_ID);
	mysql_delete("DELETE FROM `questionarios` WHERE `id`='$quest_ID';");
}


/*
doUpdateQuestionnaire($id);
Descrição: Remove um questionario
*/
function doUpdateQuestionnaire($quest_ID, $title) {
	$quest_ID = sanitize($quest_ID);
	mysql_update("UPDATE `questionarios` SET `titulo`=".$title.", `data_criacao`='".date("Y-m-d")."' WHERE `id`=".$quest_ID.";");
}





/******************************************** */

		/*** QUESTÕES ***\ 

/******************************************** */



/* getQuestionData($quest_ID, campo1, campo2...);
// captura as colunas da tabela Account e joga num array
	MODO DE USO
	$account = getQuestionData(quest_ID, 'password', 'email');
	echo $account['email']; ele vai retorna com o email
*/
function getQuestionData($quest_ID) {
	$data = array();
	$quest_ID = sanitize($quest_ID);
	
	$func_num_args = func_num_args();
	$func_get_args = func_get_args();
	
	if ($func_num_args > 1)  {
		unset($func_get_args[0]);
		
		$fields = '`'. implode('`, `', $func_get_args) .'`';
		return mysql_select_single("SELECT $fields FROM `perguntas` WHERE `id` = '".$quest_ID."' LIMIT 1;");
	} else 
	return false;
}

/*
isQuestionExist($quest_ID)
Descrição: Verifica se existe a pergunta, retorna true ou false
*/
function isQuestionExist($quest_ID) {
	$query = mysql_select_single("SELECT `id` FROM `perguntas` WHERE `id`='$quest_ID';");

	return ($query) ? true : false;
}



/*
getQuestionQuestionnaireID($quest_ID)
Descrição: Retorna o ID do questionario da pergunta
*/
function getQuestionID($quest_ID) {
	$data = getQuestionData($quest_ID, 'questionario_id');

	return ($data !== false) ? $data['questionario_id'] : false;
}


/*
getQuestionDescription($quest_ID)
Descrição: Retorna a descricao da pergunta
*/
function getQuestionDescription($quest_ID) {
	$data = getQuestionData($quest_ID, 'descricao');

	return ($data !== false) ? $data['descricao'] : false;
}


/*
getQuestionDateCreated($quest_ID)
Descrição: Retorna a data de criacao da pergunta
*/
function getQuestionDateCreated($quest_ID) {
	$data = getQuestionData($quest_ID, 'data_criacao');

	return ($data !== false) ? $data['data_criacao'] : false;
}


/******************************************** */

		/*** RESPOSTAS ***\ 

/******************************************** */




/* getAnswerData($answer_ID, campo1, campo2...);
// captura as colunas da tabela Account e joga num array
	MODO DE USO
	$account = getAnswerData(answer_ID, 'password', 'email');
	echo $account['email']; ele vai retorna com o email
*/
function getAnswerData($answer_ID) {
	$data = array();
	$answer_ID = sanitize($answer_ID);
	
	$func_num_args = func_num_args();
	$func_get_args = func_get_args();
	
	if ($func_num_args > 1)  {
		unset($func_get_args[0]);
		
		$fields = '`'. implode('`, `', $func_get_args) .'`';
		return mysql_select_single("SELECT $fields FROM `respostas` WHERE `id` = '".$answer_ID."' LIMIT 1;");
	} else 
	return false;
}

/*
isAnswerExist($pID, $rID);
Descrição: Verifica se existe a pergunta, retorna true ou false
*/
function isAnswerExist($pID, $rID) {
	$query = mysql_select_single("SELECT `id` FROM `respostas` WHERE `pergunta_id`='$pID' and `id`='$rID';");
	
	return ($query) ? $query['id'] : false;
}


/*
getAnswerQuestionID($pID, $nResponse)
Descrição: Retorna o ID da resposta da pergunta X
*/
function getAnswerQuestionID($pID, $nResponse) {
	$query = mysql_select_multi("SELECT `id` FROM `respostas` WHERE `pergunta_id`='$pID';");
	
	return ($query) ? $query[$nResponse]['id'] : false;
}

/*
getAnswerDescription($pID, $nResponse);
retorna a DESCRICAO da resposta x baseado na pergunta
*/

function getAnswerDescription($pID, $nResponse) {
	$query = mysql_select_multi("SELECT `descricao` FROM `respostas` WHERE `pergunta_id`='$pID';");
	return ($query) ? $query[$nResponse]['descricao'] : false;
}

/*
getAnswerDateCreated($pID, $nResponse);
retorna a data de criação da resposta x baseado na pergunta
*/
function getAnswerDateCreated($pID, $nResponse) {
	$query = mysql_select_multi("SELECT `data_criacao` FROM `respostas` WHERE `pergunta_id`='$pID';");
	return ($query) ? $query[$nResponse]['data_criacao'] : false;
}


/******************************************** */

		/*** USUARIOS RESPOSTAS ***\ 

/******************************************** */





/* getUserAnswerData($answer_ID, campo1, campo2...);
// captura as colunas da tabela Account e joga num array
	MODO DE USO
	$account = getUserAnswerData(answer_ID, 'password', 'email');
	echo $account['email']; ele vai retorna com o email
*/
function getUserAnswerData($answer_ID) {
	$data = array();
	$answer_ID = sanitize($answer_ID);
	
	$func_num_args = func_num_args();
	$func_get_args = func_get_args();
	
	if ($func_num_args > 1)  {
		unset($func_get_args[0]);
		
		$fields = '`'. implode('`, `', $func_get_args) .'`';
		return mysql_select_single("SELECT $fields FROM `usuarios_gabarito` WHERE `id` = '".$answer_ID."' LIMIT 1;");
	} else 
	return false;
}

/*
isUserAnswerExist($answer_ID)
Descrição: Verifica se usuario respondeu a pergunta, retorna true ou false
*/
function isUserAnswerExist($answer_ID) {
	$query = mysql_select_single("SELECT `id` FROM `usuarios_gabarito` WHERE `id`='$answer_ID';");

	return ($query) ? true : false;
}

/*
getUserAnswerQuestionID($answer_ID)
Descrição: Retorna o ID da pergunta da resposta do usuario
*/
function getUserAnswerQuestionID($answer_ID) {
	$data = getUserAnswerData($answer_ID, 'pergunta_id');

	return ($data !== false) ? $data['pergunta_id'] : false;
}


/*
getUserAnswerDescription($answer_ID)
Descrição: Retorna a descricao da resposta do usuario
*/
function getUserAnswerDescription($user_ID, $pID) {
	$query = mysql_select_single("SELECT `observacao` FROM `usuarios_gabarito` WHERE `pergunta_id`='$pID' and `usuario_id`='$user_ID';");
	
	return ($query) ? $query['observacao'] : false;
}



/*
getUserAnswerDateCreated($answer_ID)
Descrição: Retorna a data de criacao da resposta do usuario
*/
function getUserAnswerDateCreated($answer_ID) {
	$data = getUserAnswerData($answer_ID, 'data_criacao');

	return ($data !== false) ? $data['data_criacao'] : false;
}


/*
getUserAnswerUserID($answer_ID)
Descrição: Retorna o id do usuario que respondeu a resposta ID x
*/
function getUserAnswerUserID($answer_ID) {
	$data = getUserAnswerData($answer_ID, 'usuario_id');

	return ($data !== false) ? $data['usuario_id'] : false;
}


/*
getUserAnswerAnswerID($answer_ID)
Descrição: Retorna o id da resposta que respondeu a resposta ID x
*/
function getUserAnswerAnswerID($user_ID, $pID) {
	$query = mysql_select_single("SELECT `resposta_id` FROM `usuarios_gabarito` WHERE `pergunta_id`='$pID' and `usuario_id`='$user_ID';");
		
	return ($query) ? $query['resposta_id'] : false;
}



/*
doAddUserAnswer($pergunta_id, $usuario_id, $resposta_id, $observacao, $data_criacao)
Descrição: add a resposta do usuario na tabela gabarito, se já existi ele altera a resposta
*/
function doAddUserAnswer($pergunta_id, $usuario_id, $resposta_id, $observacao, $data_criacao) {

    if(getUserAnswerAnswerID($usuario_id, $pergunta_id) === false) 
        return mysql_insert("INSERT INTO `usuarios_gabarito` (`pergunta_id`, `usuario_id`, `resposta_id`, `observacao`, `data_criacao`) VALUES ($pergunta_id, ".(INT)$usuario_id.", ".(INT)$resposta_id.", '".$observacao."', ".$data_criacao.");");
    else
        return mysql_update("UPDATE `usuarios_gabarito` SET `resposta_id`=".$resposta_id.", `observacao`='".$observacao."', `data_criacao`=".$data_criacao." WHERE `usuario_id`=".$usuario_id." and `pergunta_id`=".$pergunta_id.";");
}

?>