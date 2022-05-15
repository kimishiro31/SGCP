<?php 
require_once 'engine/init.php';
doProtect();
getPageAccess($user_data['id'], 2);
$titlepage = "Controle de Usuário";
include 'layout/overall/header.php';

if(isUserExist($_GET['data']) !== false) {
    $user_ID = isUserExist($_GET['data']);
    $account_ID = getAccountID($user_ID);

        /*********************************************************
        **
        ** VALIDAÇÃO DO FORMULARIO DE DELETE DO USUARIO
        **
        **********************************************************/

        if(isset($_GET['delete']) && ($_GET['delete'] == 'yes') && getToken('tokenConfirm')) {
            destroyToken('tokenConfirm');
            destroyToken('tokenDelete');
            echo output_msgs('O usuário e todos os dados relacionados ao mesmo, foi deletado com sucesso!!');
            header('refresh: 2, usersList.php');
            deleteUser($_POST['validator']);
        }
        elseif(isset($_GET['delete']) && getToken('tokenDelete')) {
            setToken('tokenConfirm');
            echo output_warning('Você está prestes a deletar todos os dados que é ligado a esse usuário, você tem certeza disso?
                <form action="userViewer.php?data='.isUserExist($_POST['validator']).'&delete=yes" method="POST">
                    <input name="validator" type="text" value="'.$_POST['validator'].'" hidden/>
                    <input name="token" type="text" value="'.addToken('tokenConfirm').'" hidden/>
                    <input type="submit" class="enterButton" value="CONFIRMAR"/>
                    <a href="userViewer.php?data='.isUserExist($_POST['validator']).'">
                        <input type="button" class="backButton" value="CANCELAR"/>
                    </a>
                </form>
            ');
        }

        
                
        /*********************************************************
        **
        ** VALIDAÇÃO DO FORMULARIO DE FICHA ANAMNESE
        **
        **********************************************************/

        /* VALIDAÇÕES DO ANAMNESE*/
        if (empty($_POST) === false && getToken('tokenAnamnese')) {
            $required_fields = array('r1', 'r2', 'r3', 'r4', 'r5', 'r6', 'r7', 'r8', 'r9', 'r10', 'r11', 'r12', 'r13', 'r14', 'r15', 'r16');

            foreach($_POST as $key => $value) {
                if (empty($value) && in_array($key, $required_fields) === true) {
                    $errors[] = "Obrigatório o preenchimento de todos os campos.";
                    break 1;
                }
            }
			
         
            if (((int)$_POST['r1']) == getAnswerQuestionID(1, 0) && empty($_POST['obsR1'])) {
                $errors[] = "Preencha com uma numeração de calçado.";
            } 
            elseif (((int)$_POST['r1']) == getAnswerQuestionID(1, 0) && is_numeric($_POST['obsR1']) === false) {
                $errors[] = "Preencha com uma numeração de calçado valida.";
            }
			elseif (isAnswerExist(1, $_POST['r1']) === false) {
                $errors[] = "Tipo de calçado inexistente, se o erro persistir contate o administrador.";		
			}
			
			if (isAnswerExist(2, $_POST['r2']) === false) {
                $errors[] = "Tipo de meia inexistente, se o erro persistir contate o administrador.";				
			}

            
            if (((int)$_POST['r3']) == getAnswerQuestionID(3, 0) && empty($_POST['obsR3'])) {
                $errors[] = "Preencha a localização da cirurgia que tem nos membros.";
            } 
            elseif (((int)$_POST['r3']) == getAnswerQuestionID(3, 0) && preg_match("/^[a-zA-Z0-9 ]+$/", $_POST['obsR3']) === false) {
                $errors[] = "No campo de especificação da cirurgia só é aceito caracteres alfanuméricos.";
            }
			elseif (isAnswerExist(3, $_POST['r3']) === false) {
                $errors[] = "Resposta de cirurgia inexistente, se o erro persistir contate o administrador.";
			}

            
            
            if (((int)$_POST['r4']) == getAnswerQuestionID(4, 0) && empty($_POST['obsR4'])) {
                $errors[] = "Preencha com o tipo de esporte práticado.";
            } 
            elseif (((int)$_POST['r4']) == getAnswerQuestionID(4, 0) && preg_match("/^[a-zA-Z0-9 ]+$/", $_POST['obsR4']) === false) {
                $errors[] = "No campo de prática de esporte só é aceito caracteres alfanuméricos.";
            }
			elseif (isAnswerExist(4, $_POST['r4']) === false) {
                $errors[] = "Resposta de prática de esporte inexistente, se o erro persistir contate o administrador.";
			}
           


            if (((int)$_POST['r5']) == getAnswerQuestionID(5, 0) && empty($_POST['obsR5'])) {
                $errors[] = "Preencha a especificação de um remédio valido.";
            } 
            elseif (((int)$_POST['r5']) == getAnswerQuestionID(5, 0) && preg_match("/^[a-zA-Z0-9 ]+$/", $_POST['obsR5']) === false) {
                $errors[] = "No campo de especificação do remédio só é aceito caracteres alfanuméricos.";
            }
			elseif (isAnswerExist(5, $_POST['r5']) === false) {
                $errors[] = "Resposta de medicamento inexistente, se o erro persistir contate o administrador.";	
			}
            

            if (((int)$_POST['r6']) == getAnswerQuestionID(6, 0) && empty($_POST['obsR6'])) {
                $errors[] = "Preencha a quantidade de semanas da gestação.";
            } 
            elseif (((int)$_POST['r6']) == getAnswerQuestionID(6, 0) && preg_match("/^[a-zA-Z0-9 ]+$/", $_POST['obsR6']) === false) {
                $errors[] = "No campo de especificação de semanas de gestão só é aceito caracteres alfanuméricos.";
            }
			elseif (isAnswerExist(6, $_POST['r6']) === false) {
                $errors[] = "Resposta de gestante inexistente, se o erro persistir contate o administrador.";			
			}

            
            if (((int)$_POST['r7']) == getAnswerQuestionID(7, 0) && empty($_POST['obsR7'])) {
                $errors[] = "Preencha qual alergia você tem.";
            } 
            elseif (((int)$_POST['r7']) == getAnswerQuestionID(7, 0) && preg_match("/^[a-zA-Z0-9 ]+$/", (int)$_POST['obsR7']) === false) {
                $errors[] = "No campo de especificação da alergia só é aceito caracteres alfanuméricos.";
            }
			elseif (isAnswerExist(7, (int)$_POST['r7']) === false) {
                $errors[] = "Resposta de alergia inexistente, se o erro persistir contate o administrador.";			
			}

            
            if (((int)$_POST['r8']) == getAnswerQuestionID(8, 0) && empty($_POST['obsR8'])) {
                $errors[] = "Preencha a especificação de sensibilidade a dor.";
            } 
            elseif (((int)$_POST['r8']) == getAnswerQuestionID(8, 0) && preg_match("/^[a-zA-Z0-9 ]+$/", $_POST['obsR8']) === false) {
                $errors[] = "No campo de especificação da sensibilidade a dor só é aceito caracteres alfanuméricos.";
            }
			elseif (isAnswerExist(8, (int)$_POST['r8']) === false) {
                $errors[] = "Resposta de sensabilidade a dor inexistente, se o erro persistir contate o administrador.";		
			}
			
			
			if (isAnswerExist(9, (int)$_POST['r9']) === false) {
                $errors[] = "Resposta de hipo/hipertensão inexistente, se o erro persistir contate o administrador.";			
			}
			
			if (isAnswerExist(10, (int)$_POST['r10']) === false) {
                $errors[] = "Resposta de diabete inexistente, se o erro persistir contate o administrador.";		
			}
			
			if (isAnswerExist(11, (int)$_POST['r11']) === false) {
                $errors[] = "Resposta de hanseniase inexistente, se o erro persistir contate o administrador.";		
			}
			
			if (isAnswerExist(12, (int)$_POST['r12']) === false) {
                $errors[] = "Resposta de cardiopatia inexistente, se o erro persistir contate o administrador.";		
			}
			
			if (isAnswerExist(13, (int)$_POST['r13']) === false) {
                $errors[] = "Resposta de cancer inexistente, se o erro persistir contate o administrador.";	
			}
			
			if (isAnswerExist(14, (int)$_POST['r14']) === false) {
                $errors[] = "Resposta de marcapasso/pino inexistente, se o erro persistir contate o administrador.";		
			}
			
			if (isAnswerExist(15, (int)$_POST['r15']) === false) {
                $errors[] = "Resposta de distúrbio circulatório inexistente, se o erro persistir contate o administrador.";		
			}
			
			if (isAnswerExist(16, (int)$_POST['r16']) === false) {
                $errors[] = "Resposta de hepatite inexistente, se o erro persistir contate o administrador.";		
			}
            
        }

        if (empty($_POST) === false && getToken('tokenAnamnese') && empty($errors)) {
			$user_id = $_GET['data'];
            $obsR3 = (((int)$_POST['r3']) != getAnswerQuestionID(3, 0)) ? '' : $_POST['obsR3'];
            $obsR4 = (((int)$_POST['r4']) != getAnswerQuestionID(4, 0)) ? '' : $_POST['obsR4'];
            $obsR5 = (((int)$_POST['r5']) != getAnswerQuestionID(5, 0)) ? '' : $_POST['obsR5'];
            $obsR6 = (((int)$_POST['r6']) != getAnswerQuestionID(6, 0)) ? '' : $_POST['obsR6'];
            $obsR7 = (((int)$_POST['r7']) != getAnswerQuestionID(7, 0)) ? '' : $_POST['obsR7'];
            $obsR8 = (((int)$_POST['r8']) != getAnswerQuestionID(8, 0)) ? '' : $_POST['obsR8'];

            $import_data = array(
						1 => array(
							'USUARIO_ID' => $user_id, 
							'RESPOSTA_ID' => (int)$_POST['r1'], 
							'OBSERVACAO' => strtoupper($_POST['obsR1']), 
							'DATA_CRIACAO' => time()
							),
						2 => array(
							'USUARIO_ID' => $user_id, 
							'RESPOSTA_ID' => (int)$_POST['r2'], 
							'OBSERVACAO' => '', 
							'DATA_CRIACAO' => time()
							),
						3 => array(
							'USUARIO_ID' => $user_id, 
							'RESPOSTA_ID' => (int)$_POST['r3'], 
							'OBSERVACAO' => strtoupper($obsR3), 
							'DATA_CRIACAO' => time()
							),
						4 => array(
							'USUARIO_ID' => $user_id, 
							'RESPOSTA_ID' => (int)$_POST['r4'], 
							'OBSERVACAO' => strtoupper($obsR4), 
							'DATA_CRIACAO' => time()
							),
						5 => array(
							'USUARIO_ID' => $user_id, 
							'RESPOSTA_ID' => (int)$_POST['r5'], 
							'OBSERVACAO' => strtoupper($obsR5), 
							'DATA_CRIACAO' => time()
							),
						6 => array(
							'USUARIO_ID' => $user_id, 
							'RESPOSTA_ID' => (int)$_POST['r6'], 
							'OBSERVACAO' => strtoupper($obsR6), 
							'DATA_CRIACAO' => time()
							),
						7 => array(
							'USUARIO_ID' => $user_id, 
							'RESPOSTA_ID' => (int)$_POST['r7'], 
							'OBSERVACAO' => strtoupper($obsR7), 
							'DATA_CRIACAO' => time()
							),
						8 => array(
							'USUARIO_ID' => $user_id, 
							'RESPOSTA_ID' => (int)$_POST['r8'], 
							'OBSERVACAO' => strtoupper($obsR8), 
							'DATA_CRIACAO' => time()
							),
						9 => array(
							'USUARIO_ID' => $user_id, 
							'RESPOSTA_ID' => (int)$_POST['r9'], 
							'OBSERVACAO' => '', 
							'DATA_CRIACAO' => time()
							),
						10 => array(
							'USUARIO_ID' => $user_id, 
							'RESPOSTA_ID' => (int)$_POST['r10'], 
							'OBSERVACAO' => '', 
							'DATA_CRIACAO' => time()
							),
						11 => array(
							'USUARIO_ID' => $user_id, 
							'RESPOSTA_ID' => (int)$_POST['r11'], 
							'OBSERVACAO' => '', 
							'DATA_CRIACAO' => time()
							),
						12 => array(
							'USUARIO_ID' => $user_id, 
							'RESPOSTA_ID' => (int)$_POST['r12'], 
							'OBSERVACAO' => '', 
							'DATA_CRIACAO' => time()
							),
						13 => array(
							'USUARIO_ID' => $user_id, 
							'RESPOSTA_ID' => (int)$_POST['r13'], 
							'OBSERVACAO' => '', 
							'DATA_CRIACAO' => time()
							),
						14 => array(
							'USUARIO_ID' => $user_id, 
							'RESPOSTA_ID' => (int)$_POST['r14'], 
							'OBSERVACAO' => '', 
							'DATA_CRIACAO' => time()
							),
						15 => array(
							'USUARIO_ID' => $user_id, 
							'RESPOSTA_ID' => (int)$_POST['r15'], 
							'OBSERVACAO' => '', 
							'DATA_CRIACAO' => time()
							),
						16 => array(
							'USUARIO_ID' => $user_id, 
							'RESPOSTA_ID' => (int)$_POST['r16'], 
							'OBSERVACAO' => '', 
							'DATA_CRIACAO' => time()
							),
 			);
			
			foreach($import_data as $key => $value) {
                doAddUserAnswer($key, $user_id, $value['RESPOSTA_ID'], $value['OBSERVACAO'], $value['DATA_CRIACAO']);
			}
                echo output_msgs("Ficha anamnese preenchida com sucesso!!");
        }

                
        /*********************************************************
        **
        ** VALIDAÇÃO DO FORMULARIO DE VISTA PLANTAR
        **
        **********************************************************/

        if (empty($_POST) === false && getToken('tokenPlant')) {
            $arrayPos = array('posPE01', 'posPE02', 'posPE03', 'posPE04', 'posPE05', 'posPD01', 'posPD02', 'posPD03', 'posPD04', 'posPD05');
            $arrayPat = array('patPE01', 'patPE02', 'patPE03', 'patPE04', 'patPE05', 'patPD01', 'patPD02', 'patPD03', 'patPD04', 'patPD05');

            foreach($_POST as $key => $value) {
                if ($key !== 'token') {
                    if ($value != 0) {
                        if (in_array($key, $arrayPat) && isPathologyExist($value) === false) {
                            $errors[] = "Houve um erro ao validar uma ou mais patologia, reinicie a página, se o erro persistir contate o administrador.";
                            break 1;
                        } 
                        elseif (in_array($key, $arrayPos) && ($value < 0 || $value > 16)) {
                            $errors[] = "Houve um erro ao validar uma ou mais posição, reinicie a página, se o erro persistir contate o administrador.";
                            break 1;
                        }
                        elseif (in_array($key, $arrayPos) && $_POST[$arrayPat[array_search($key, $arrayPos)]] == 0) {
                            $errors[] = "Favor preencher com a patologia na posição escolhida.";
                            break 1;
                        }
                        elseif (in_array($key, $arrayPat) && $_POST[$arrayPos[array_search($key, $arrayPat)]] == 0) {
                            $errors[] = "Favor preencher com a posição na patologia escolhida.";
                            break 1;
                        }
                    }    
                }
            }
        }

        if (empty($_POST) === false && getToken('tokenPlant') && empty($errors))  {
			$user_id = $_GET['data'];
            $import_data = array(
                // PÉ ESQUERDO
                1 => array(
                    'PE' => 'PE',
                    'PAT_ID' => (((int)$_POST['patPE01']) != 0) ? $_POST['patPE01'] : 0,
                    'PAT_POS' => (((int)$_POST['posPE01']) != 0) ? $_POST['posPE01'] : 0,
                    'PAT_TYPE' => 'VP'  
                ),
                2 => array(
                    'PE' => 'PE',
                    'PAT_ID' => (((int)$_POST['patPE02']) != 0) ? $_POST['patPE02'] : 0,
                    'PAT_POS' => (((int)$_POST['posPE02']) != 0) ? $_POST['posPE02'] : 0,
                    'PAT_TYPE' => 'VP'  
                ),
                3 => array(
                    'PE' => 'PE',
                    'PAT_ID' => (((int)$_POST['patPE03']) != 0) ? $_POST['patPE03'] : 0,
                    'PAT_POS' => (((int)$_POST['posPE03']) != 0) ? $_POST['posPE03'] : 0,
                    'PAT_TYPE' => 'VP'  
                ),
                4 => array(
                    'PE' => 'PE',
                    'PAT_ID' => (((int)$_POST['patPE04']) != 0) ? $_POST['patPE04'] : 0,
                    'PAT_POS' => (((int)$_POST['posPE04']) != 0) ? $_POST['posPE04'] : 0,
                    'PAT_TYPE' => 'VP'  
                ),
                5 => array(
                    'PE' => 'PE',
                    'PAT_ID' => (((int)$_POST['patPE05']) != 0) ? $_POST['patPE05'] : 0,
                    'PAT_POS' => (((int)$_POST['posPE05']) != 0) ? $_POST['posPE05'] : 0,
                    'PAT_TYPE' => 'VP'  
                ),
                // PÉ DIREITO
                6 => array(
                    'PE' => 'PD',
                    'PAT_ID' => (((int)$_POST['patPD01']) != 0) ? $_POST['patPD01'] : 0,
                    'PAT_POS' => (((int)$_POST['posPD01']) != 0) ? $_POST['posPD01'] : 0,
                    'PAT_TYPE' => 'VP'  
                ),
                7 => array(
                    'PE' => 'PD',
                    'PAT_ID' => (((int)$_POST['patPD02']) != 0) ? $_POST['patPD02'] : 0,
                    'PAT_POS' => (((int)$_POST['posPD02']) != 0) ? $_POST['posPD02'] : 0,
                    'PAT_TYPE' => 'VP'  
                ),
                8 => array(
                    'PE' => 'PD',
                    'PAT_ID' => (((int)$_POST['patPD03']) != 0) ? $_POST['patPD03'] : 0,
                    'PAT_POS' => (((int)$_POST['posPD03']) != 0) ? $_POST['posPD03'] : 0,
                    'PAT_TYPE' => 'VP'  
                ),
                9 => array(
                    'PE' => 'PD',
                    'PAT_ID' => (((int)$_POST['patPD04']) != 0) ? $_POST['patPD04'] : 0,
                    'PAT_POS' => (((int)$_POST['posPD04']) != 0) ? $_POST['posPD04'] : 0,
                    'PAT_TYPE' => 'VP'  
                ),
                10 => array(
                    'PE' => 'PD',
                    'PAT_ID' => (((int)$_POST['patPD05']) != 0) ? $_POST['patPD05'] : 0,
                    'PAT_POS' => (((int)$_POST['posPD05']) != 0) ? $_POST['posPD05'] : 0,
                    'PAT_TYPE' => 'VP'  
                )

            );
         
            if(count($import_data) > 0) {
                $import_data = array_combine(range(1, count($import_data)), array_values($import_data));
                foreach($import_data as $key => $value) {
                    doAddUserPathology($user_id, $value['PE'], $key, $value['PAT_ID'], $value['PAT_POS'], $value['PAT_TYPE']);
                }
            }
            echo output_msgs("As informações foram atualizadas com sucesso!!<br/> Direcionando para a pagina do perfil.");
        }

        
                
        /*********************************************************
        **
        ** VALIDAÇÃO DO FORMULARIO DE VISTA DORSAL
        **
        **********************************************************/

        if (empty($_POST) === false && getToken('tokenDorsal')) {
            $arrayPos = array('posPE01', 'posPE02', 'posPE03', 'posPE04', 'posPE05', 'posPD01', 'posPD02', 'posPD03', 'posPD04', 'posPD05');
            $arrayPat = array('patPE01', 'patPE02', 'patPE03', 'patPE04', 'patPE05', 'patPD01', 'patPD02', 'patPD03', 'patPD04', 'patPD05');

            foreach($_POST as $key => $value) {
                if ($key !== 'token') {
                    if ($value != 0) {
                        if (in_array($key, $arrayPat) && isPathologyExist($value) === false) {
                            $errors[] = "Houve um erro ao validar uma ou mais patologia, reinicie a página, se o erro persistir contate o administrador.";
                            break 1;
                        } 
                        elseif (in_array($key, $arrayPos) && ($value < 0 || $value > 16)) {
                            $errors[] = "Houve um erro ao validar uma ou mais posição, reinicie a página, se o erro persistir contate o administrador.";
                            break 1;
                        }
                        elseif (in_array($key, $arrayPos) && $_POST[$arrayPat[array_search($key, $arrayPos)]] == 0) {
                            $errors[] = "Favor preencher com a patologia na posição escolhida.";
                            break 1;
                        }
                        elseif (in_array($key, $arrayPat) && $_POST[$arrayPos[array_search($key, $arrayPat)]] == 0) {
                            $errors[] = "Favor preencher com a posição na patologia escolhida.";
                            break 1;
                        }
                    }    
                }
            }
        }

        if (empty($_POST) === false && getToken('tokenDorsal') && empty($errors))  {
			$user_id = $_GET['data'];
            $import_data = array(
                // PÉ ESQUERDO
                1 => array(
                    'PE' => 'PE',
                    'PAT_ID' => (((int)$_POST['patPE01']) != 0) ? $_POST['patPE01'] : 0,
                    'PAT_POS' => (((int)$_POST['posPE01']) != 0) ? $_POST['posPE01'] : 0,
                    'PAT_TYPE' => 'VD'  
                ),
                2 => array(
                    'PE' => 'PE',
                    'PAT_ID' => (((int)$_POST['patPE02']) != 0) ? $_POST['patPE02'] : 0,
                    'PAT_POS' => (((int)$_POST['posPE02']) != 0) ? $_POST['posPE02'] : 0,
                    'PAT_TYPE' => 'VD'  
                ),
                3 => array(
                    'PE' => 'PE',
                    'PAT_ID' => (((int)$_POST['patPE03']) != 0) ? $_POST['patPE03'] : 0,
                    'PAT_POS' => (((int)$_POST['posPE03']) != 0) ? $_POST['posPE03'] : 0,
                    'PAT_TYPE' => 'VD'  
                ),
                4 => array(
                    'PE' => 'PE',
                    'PAT_ID' => (((int)$_POST['patPE04']) != 0) ? $_POST['patPE04'] : 0,
                    'PAT_POS' => (((int)$_POST['posPE04']) != 0) ? $_POST['posPE04'] : 0,
                    'PAT_TYPE' => 'VD'  
                ),
                5 => array(
                    'PE' => 'PE',
                    'PAT_ID' => (((int)$_POST['patPE05']) != 0) ? $_POST['patPE05'] : 0,
                    'PAT_POS' => (((int)$_POST['posPE05']) != 0) ? $_POST['posPE05'] : 0,
                    'PAT_TYPE' => 'VD'  
                ),
                // PÉ DIREITO
                6 => array(
                    'PE' => 'PD',
                    'PAT_ID' => (((int)$_POST['patPD01']) != 0) ? $_POST['patPD01'] : 0,
                    'PAT_POS' => (((int)$_POST['posPD01']) != 0) ? $_POST['posPD01'] : 0,
                    'PAT_TYPE' => 'VD'  
                ),
                7 => array(
                    'PE' => 'PD',
                    'PAT_ID' => (((int)$_POST['patPD02']) != 0) ? $_POST['patPD02'] : 0,
                    'PAT_POS' => (((int)$_POST['posPD02']) != 0) ? $_POST['posPD02'] : 0,
                    'PAT_TYPE' => 'VD'  
                ),
                8 => array(
                    'PE' => 'PD',
                    'PAT_ID' => (((int)$_POST['patPD03']) != 0) ? $_POST['patPD03'] : 0,
                    'PAT_POS' => (((int)$_POST['posPD03']) != 0) ? $_POST['posPD03'] : 0,
                    'PAT_TYPE' => 'VD'  
                ),
                9 => array(
                    'PE' => 'PD',
                    'PAT_ID' => (((int)$_POST['patPD04']) != 0) ? $_POST['patPD04'] : 0,
                    'PAT_POS' => (((int)$_POST['posPD04']) != 0) ? $_POST['posPD04'] : 0,
                    'PAT_TYPE' => 'VD'  
                ),
                10 => array(
                    'PE' => 'PD',
                    'PAT_ID' => (((int)$_POST['patPD05']) != 0) ? $_POST['patPD05'] : 0,
                    'PAT_POS' => (((int)$_POST['posPD05']) != 0) ? $_POST['posPD05'] : 0,
                    'PAT_TYPE' => 'VD'  
                )

            );
            			
            if(count($import_data) > 0) {
                $import_data = array_combine(range(1, count($import_data)), array_values($import_data));
                foreach($import_data as $key => $value) {
                    doAddUserPathology($user_id, $value['PE'], $key, $value['PAT_ID'], $value['PAT_POS'], $value['PAT_TYPE']);
                }
            }
            echo output_msgs("As informações foram atualizadas com sucesso!!<br/> Direcionando para a pagina do perfil.");
        }

                
        /*********************************************************
        **
        ** RETORNO DOS ERROS EM CASO DE ALGUMA VALIDAÇÃO TER DADO
        ** ERRADO
        **********************************************************/

        if (empty($errors) === false) {
            header("HTTP/1.1 401 Not Found");
            echo output_errors($errors);
        }

        
                
        /*********************************************************
        **
        ** VERIFICA QUAL A PAGINA(DIV) QUE SERÁ ABERTA AO REINICIAR
        ** A MAQUINA
        **********************************************************/
        if (getToken('tokenAnamnese')) {
            echo '
            <script>
                $(document).ready(function() {
                    document.querySelectorAll(".anamnese").forEach(el => el.classList.toggle("selected"));
                    document.querySelectorAll(".data").forEach(el => el.classList.toggle("selected"));
                });
            </script>
            <style>
                #dataContent {
                    display: none;
                }
                #anamneseContent {
                    display: block;
                }
            </style>
            ';
        }
        elseif (getToken('tokenConfirmSchedule') || getToken('tokenSchedule') || getToken('tokenCancelSchedule') || getToken('tokenDelSchedule') || isset($_GET['scheduleAlter'])) {
            echo '
            <script>
                $(document).ready(function() {
                    document.querySelectorAll(".scheduling").forEach(el => el.classList.toggle("selected"));
                    document.querySelectorAll(".data").forEach(el => el.classList.toggle("selected"));
                });
            </script>
            <style>
                #dataContent {
                    display: none;
                }
                #schedulingContent {
                    display: block;
                }
            </style>
            ';
        }
        elseif (getToken('tokenPlant')) {
            echo '
            <script>
                $(document).ready(function() {
                    document.querySelectorAll(".plant").forEach(el => el.classList.toggle("selected"));
                    document.querySelectorAll(".data").forEach(el => el.classList.toggle("selected"));
                });
            </script>
            <style>
                #dataContent {
                    display: none;
                }
                #plantContent {
                    display: block;
                }
            </style>
            ';
        }
        elseif (getToken('tokenDorsal')) {
            echo '
            <script>
                $(document).ready(function() {
                    document.querySelectorAll(".dorsal").forEach(el => el.classList.toggle("selected"));
                    document.querySelectorAll(".data").forEach(el => el.classList.toggle("selected"));
                });
            </script>
            <style>
                #dataContent {
                    display: none;
                }
                #dorsalContent {
                    display: block;
                }
            </style>
            ';
        }
        elseif (getToken('tokenScheduling')) {
            echo '
            <script>
                $(document).ready(function() {
                    document.querySelectorAll(".scheduling").forEach(el => el.classList.toggle("selected"));
                    document.querySelectorAll(".data").forEach(el => el.classList.toggle("selected"));
                });
            </script>
            <style>
                #dataContent {
                    display: none;
                }
                #schedulingContent {
                    display: block;
                }
            </style>
            ';
        }
?>
        <!-- JAVA SCRIPT -->
        <script>
            $()
            function activeClass(cla) {
            const c = document.querySelectorAll('.options');
                c.forEach(el => el.classList.toggle('selected', el === cla));
                
            }


            $(function(){
                $(".data").click(function(e) {
                    if(document.getElementById("dataContent").style.display != 'block') {
                        document.getElementById("dataContent").style.display = 'block';
                        document.getElementById("anamneseContent").style.display = 'none';
                        document.getElementById("plantContent").style.display = 'none';
                        document.getElementById("dorsalContent").style.display = 'none';
                        document.getElementById("schedulingContent").style.display = 'none';
                    }
                });
            });

            $(function(){
                $(".anamnese").click(function(e) {
                    if(document.getElementById("anamneseContent").style.display != 'block') {
                        document.getElementById("dataContent").style.display = 'none';
                        document.getElementById("anamneseContent").style.display = 'block';
                        document.getElementById("plantContent").style.display = 'none';
                        document.getElementById("dorsalContent").style.display = 'none';
                        document.getElementById("schedulingContent").style.display = 'none';
                    }
                });
            });            

            $(function(){
                $(".plant").click(function(e) {
                    if(document.getElementById("plantContent").style.display != 'block') {
                        document.getElementById("dataContent").style.display = 'none';
                        document.getElementById("anamneseContent").style.display = 'none';
                        document.getElementById("plantContent").style.display = 'block';
                        document.getElementById("dorsalContent").style.display = 'none';
                        document.getElementById("schedulingContent").style.display = 'none';
                    }
                });
            });            
            $(function(){
                $(".dorsal").click(function(e) {
                    if(document.getElementById("dorsalContent").style.display != 'block') {
                        document.getElementById("dataContent").style.display = 'none';
                        document.getElementById("anamneseContent").style.display = 'none';
                        document.getElementById("plantContent").style.display = 'none';
                        document.getElementById("dorsalContent").style.display = 'block';
                        document.getElementById("schedulingContent").style.display = 'none';
                    }
                });
            });           
            $(function(){
                $(".scheduling").click(function(e) {
                    if(document.getElementById("schedulingContent").style.display != 'block') {
                        document.getElementById("dataContent").style.display = 'none';
                        document.getElementById("anamneseContent").style.display = 'none';
                        document.getElementById("plantContent").style.display = 'none';
                        document.getElementById("dorsalContent").style.display = 'none';
                        document.getElementById("schedulingContent").style.display = 'block';
                    }
                });
            });         
        </script>

        <ul id="vw-select">
            <li class="options selected data" onclick="activeClass(this)">DADOS</li>
            <li class="options anamnese" onclick="activeClass(this)">ANAMNESE</li>
            <li class="options plant" onclick="activeClass(this)">VISTA PLANTAR</li>
            <li class="options dorsal" onclick="activeClass(this)">VISTA DORSAL</li>
            <li class="options scheduling" onclick="activeClass(this)">AGENDAMENTOS</li>
            <div id="vw-border"></div>
        </ul>

        <div id="dataContent">       
            <table class="vw-table">
                <tr>
                    <td rowspan="35">
                        <div class="vw-imgFrame">
                            <a href="">
                                <img src="<?php echo getUserFolderForIMG(getUserCPF($user_ID)).'/'.getUserPhotoName($user_ID); ?>"></img>
                            </a>
                        </div>
                    </td>
                    <th colspan="3">DADOS PESSOAIS</th>
                </tr>
                <tr>
                    <td>Código:</td>
                    <td><?php echo $user_ID ?></td>
                </tr>
                <tr>
                    <td>Nome:</td>
                    <td><?php echo getUserCompleteName($user_ID) ?></td>
                </tr>
                <tr>
                    <td>Data de Nascimento:</td>
                    <td><?php echo getUserBirthDate($user_ID) ?></td>
                </tr>
                <tr>
                    <td>Gênero:</td>
                    <td><?php echo getUserGender($user_ID) ?></td>
                </tr>
                <tr>
                    <td>Nacionalidade:</td>
                    <td><?php echo getUserNacionality($user_ID) ?></td>
                </tr>
                <tr>
                    <td>Profissão:</td>
                    <td><?php echo getUserProfission($user_ID) ?></td>
                </tr>
                <tr>
                    <th colspan="3">DOCUMENTOS</th>
                </tr>
                <tr>
                    <td>CPF:</td>
                    <td><?php echo doFormatCPF(getUserCPF($user_ID)) ?></td>
                </tr>
                <tr>
                    <td>RG:</td>
                    <td><?php echo getUserRG($user_ID) ?></td>
                </tr>
                <tr>
                    <th colspan="3">ENDEREÇO</th>
                </tr>
                <tr>
                    <td>CEP:</td>
                    <td><?php echo getUserAddressCEP($user_ID) ?></td>
                </tr>
                <tr>
                    <td>Rua</td>
                    <td><?php echo getUserAddressStreet($user_ID) ?></td>
                </tr>
                <tr>
                    <td>Nº:</td>
                    <td><?php echo getUserAddressNumber($user_ID) ?></td>
                </tr>
                <tr>
                    <td>Complemento:</td>
                    <td><?php echo getUserAddressComplement($user_ID) ?></td>
                </tr>
                <tr>
                    <td>Bairro:</td>
                    <td><?php echo getUserAddressDistrict($user_ID) ?></td>
                </tr>
                <tr>
                    <td>Cidade:</td>
                    <td><?php echo getUserAddressCity($user_ID) ?></td>
                </tr>
                <tr>
                    <td>Estado:</td>
                    <td><?php echo getUserAddressUF($user_ID) ?></td>
                </tr>
                <tr>
                    <th colspan="2">CONTATOS</th>
                </tr>
                <tr>
                    <td>Telefone:</td>
                    <td><?php echo getAccountPPhone($account_ID) ?></td>
                </tr>
                <tr>
                    <td>Telefone:</td>
                    <td><?php echo getAccountSPhone($account_ID) ?></td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td><?php echo getAccountEmail($account_ID) ?></td>
                </tr>
                <tr>
                    <td>Cadastrado em:</td>
                    <td><?php echo getAccountDateCreated($account_ID) ?></td>
                </tr>
                <tr>
                    <td>Nível de Acesso: </td>
                    <td><?php echo doGroupConvertInString(getAccountGroup($account_ID)) ?></td>
                </tr>
                <tr>
                    <td>Status: </td>
                    <td><?php echo doAccountConvertStatusInString(getAccountStatus($account_ID)) ?></td>
                </tr>
                <tr>
                    <td>Login </td>
                    <td><?php echo getAccountLogin($account_ID) ?></td>
                </tr>
            </table>
            <br><br>
            <center>
                <table>
                    <tr>
                        <td>
                            <form action="userEdit.php" method="POST">
                                <input name="validator" type="text" value="<?php echo $user_ID ?>" hidden/>
                                <input type="submit" class="otherButton" value="EDITAR DADOS"/>
                            </form>
                        </td>
                        <td>
                            <form action="userViewer.php?data=<?php echo $user_ID ?>&delete" method="POST">
                                <?php setToken('tokenDelete') ?>
                                <input name="token" type="text" value="<?php echo addToken('tokenDelete') ?>" hidden/>
                                <input name="validator" type="text" value="<?php echo $user_ID ?>" hidden/>
                                <button type="submit" class="backButton">EXCLUIR USUARIO</button>
                            </form>
                        </td>
                    </tr>
                </table>
            </center>
        </div>

        <div id="anamneseContent">
            <div id="anamneseHeader">
                <div id="logoFrame">
                    <img src="https://i.pinimg.com/originals/74/e7/3e/74e73e1d80a9a3e05692c109ac883f58.png"/>
                </div>
                <div id="titleData">
                    <label id="typeClinic">Podologia</label><br>
                    <label id="typeData">Ficha Anamnese</label>
                </div>
            </div>
            
            <form action="" method="POST">
                <div id="anamneseBody">
                        <table width="100%" id="am-table">
                        <tr>
                            <td colspan="1">
                                Tipo de calçado mais utilizado:
                                <div class="txtOthers">
                                    <?php
//                                        $query = mysql_select_multi("SELECT * FROM `usuarios_gabarito` WHERE `usuario_id`=".$_GET['data'].";");
                                    ?>
                                    
                                    <input type="radio" name="r1" value="<?php echo getAnswerQuestionID(1, 0) ?>"  required <?php if (getUserAnswerAnswerID($user_ID, 1) == getAnswerQuestionID(1, 0)) echo 'checked'; ?>/> <?php echo ucfirst(getAnswerDescription(1, 0)) ?> | 
                                    <input type="radio" name="r1" value="<?php echo getAnswerQuestionID(1, 1) ?>"  required <?php if (getUserAnswerAnswerID($user_ID, 1) == getAnswerQuestionID(1, 1)) echo 'checked'; ?>/> <?php echo ucfirst(getAnswerDescription(1, 1)) ?> | 
                                        Nº <input type="text" class="smallInputTxt" name="obsR1" value="<?php echo getUserAnswerDescription($user_ID, 1); ?>"/>
                                </div>
                            </td>
                            <td colspan="1">
                                Tipo de meia mais utilizado:
                                <div class="txtOthers">
                                    <input type="radio" name="r2" value="<?php echo getAnswerQuestionID(2, 0) ?>"  required <?php if (getUserAnswerAnswerID($user_ID, 2) == getAnswerQuestionID(2, 0)) echo 'checked'; ?> /> <?php echo ucfirst(getAnswerDescription(2, 0)) ?> | 
                                    <input type="radio" name="r2" value="<?php echo getAnswerQuestionID(2, 1) ?>"  required <?php if (getUserAnswerAnswerID($user_ID, 2) == getAnswerQuestionID(2, 1)) echo 'checked'; ?> /> <?php echo ucfirst(getAnswerDescription(2, 1)) ?> 
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Cirurgia nos membros inferores?
                                    <input type="radio" name="r3" value="<?php echo getAnswerQuestionID(3, 0) ?>"  required <?php if (getUserAnswerAnswerID($user_ID, 3) == getAnswerQuestionID(3, 0)) echo 'checked'; ?> /> <?php echo ucfirst(getAnswerDescription(3, 0)) ?> | 
                                    <input type="radio" name="r3" value="<?php echo getAnswerQuestionID(3, 1) ?>"  required <?php if (getUserAnswerAnswerID($user_ID, 3) == getAnswerQuestionID(3, 1)) echo 'checked'; ?> /> <?php echo ucfirst(getAnswerDescription(3, 1)) ?> 
                                <div class="txtOthers">
                                    Especifique:
                                    <input type="text" class="mediumInputTxt" name="obsR3"  value="<?php if (getUserAnswerAnswerID($user_ID, 3) == getAnswerQuestionID(3, 0)) echo getUserAnswerDescription($user_ID, 3); ?>"/>
                                </div>
                            </td>
                            <td>
                                Prática esportes?
                                    <input type="radio" name="r4" value="<?php echo getAnswerQuestionID(4, 0) ?>"  required <?php if (getUserAnswerAnswerID($user_ID, 4) == getAnswerQuestionID(4, 0)) echo 'checked'; ?> /> <?php echo ucfirst(getAnswerDescription(4, 0)) ?> | 
                                    <input type="radio" name="r4" value="<?php echo getAnswerQuestionID(4, 1) ?>"  required <?php if (getUserAnswerAnswerID($user_ID, 4) == getAnswerQuestionID(4, 1)) echo 'checked'; ?> /> <?php echo ucfirst(getAnswerDescription(4, 1)) ?> 
                                <div class="txtOthers">
                                    Especifique:
                                    <input type="text" class="mediumInputTxt" name="obsR4"  value="<?php if (getUserAnswerAnswerID($user_ID, 4) == getAnswerQuestionID(4, 0)) echo getUserAnswerDescription($user_ID, 4); ?>"/>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Está tomando algum medicamento?
                                    <input type="radio" name="r5" value="<?php echo getAnswerQuestionID(5, 0) ?>"  required <?php if (getUserAnswerAnswerID($user_ID, 5) == getAnswerQuestionID(5, 0)) echo 'checked'; ?> /> <?php echo ucfirst(getAnswerDescription(5, 0)) ?> | 
                                    <input type="radio" name="r5" value="<?php echo getAnswerQuestionID(5, 1) ?>"  required <?php if (getUserAnswerAnswerID($user_ID, 5) == getAnswerQuestionID(5, 1)) echo 'checked'; ?> /> <?php echo ucfirst(getAnswerDescription(5, 1)) ?> 
                                <div class="txtOthers">
                                    Especifique:
                                    <input type="text" class="mediumInputTxt" name="obsR5" value="<?php if (getUserAnswerAnswerID($user_ID, 5) == getAnswerQuestionID(5, 0)) echo getUserAnswerDescription($user_ID, 5); ?>"/>
                                </div>
                            </td>
                            <td>
                                Gestante?
                                    <input type="radio" name="r6" value="<?php echo getAnswerQuestionID(6, 0) ?>"  required <?php if (getUserAnswerAnswerID($user_ID, 6) == getAnswerQuestionID(6, 0)) echo 'checked'; ?> /> <?php echo ucfirst(getAnswerDescription(6, 0)) ?> | 
                                    <input type="radio" name="r6" value="<?php echo getAnswerQuestionID(6, 1) ?>"  required <?php if (getUserAnswerAnswerID($user_ID, 6) == getAnswerQuestionID(6, 1)) echo 'checked'; ?> /> <?php echo ucfirst(getAnswerDescription(6, 1)) ?> 
                                <div class="txtOthers">
                                    Especifique:
                                    <input type="text" class="mediumInputTxt" name="obsR6"  value="<?php if (getUserAnswerAnswerID($user_ID, 6) == getAnswerQuestionID(6, 0)) echo getUserAnswerDescription($user_ID, 6); ?>"/>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Possui alguma alergia?
                                    <input type="radio" name="r7" value="<?php echo getAnswerQuestionID(7, 0) ?>"  required <?php if (getUserAnswerAnswerID($user_ID, 7) == getAnswerQuestionID(7, 0)) echo 'checked'; ?> /> <?php echo ucfirst(getAnswerDescription(7, 0)) ?> | 
                                    <input type="radio" name="r7" value="<?php echo getAnswerQuestionID(7, 1) ?>"  required <?php if (getUserAnswerAnswerID($user_ID, 7) == getAnswerQuestionID(7, 1)) echo 'checked'; ?> /> <?php echo ucfirst(getAnswerDescription(7, 1)) ?> 
                                <div class="txtOthers">
                                    Especifique:
                                    <input type="text" class="mediumInputTxt" name="obsR7"  value="<?php if (getUserAnswerAnswerID($user_ID, 7) == getAnswerQuestionID(7, 0)) echo getUserAnswerDescription($user_ID, 7); ?>"/>
                                </div>
                            </td>
                            <td>
                                Sensibilidade a dor?
                                    <input type="radio" name="r8" value="<?php echo getAnswerQuestionID(8, 0) ?>"  required <?php if (getUserAnswerAnswerID($user_ID, 8) == getAnswerQuestionID(8, 0)) echo 'checked'; ?> /> <?php echo ucfirst(getAnswerDescription(8, 0)) ?> | 
                                    <input type="radio" name="r8" value="<?php echo getAnswerQuestionID(8, 1) ?>"  required <?php if (getUserAnswerAnswerID($user_ID, 8) == getAnswerQuestionID(8, 1)) echo 'checked'; ?> /> <?php echo ucfirst(getAnswerDescription(8, 1)) ?> 
                                <div class="txtOthers">
                                    Especifique:
                                    <input type="text" class="mediumInputTxt" name="obsR8"  value="<?php if (getUserAnswerAnswerID($user_ID, 8) == getAnswerQuestionID(8, 0)) echo getUserAnswerDescription($user_ID, 8); ?>"/>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Tem hipo/hipertensão arterial?</b>
                                <div class="optionYoN">
                                    <input type="radio" name="r9" value="<?php echo getAnswerQuestionID(9, 0) ?>"  required <?php if (getUserAnswerAnswerID($user_ID, 9) == getAnswerQuestionID(9, 0)) echo 'checked'; ?> /> <?php echo ucfirst(getAnswerDescription(9, 0)) ?> | 
                                    <input type="radio" name="r9" value="<?php echo getAnswerQuestionID(9, 1) ?>"  required <?php if (getUserAnswerAnswerID($user_ID, 9) == getAnswerQuestionID(9, 1)) echo 'checked'; ?> /> <?php echo ucfirst(getAnswerDescription(9, 1)) ?> 
                                </div>
                            </td>
                            <td>
                                <b>Diabetes?</b>
                                <div class="optionYoN">
                                    <input type="radio" name="r10" value="<?php echo getAnswerQuestionID(10, 0) ?>"  required <?php if (getUserAnswerAnswerID($user_ID, 10) == getAnswerQuestionID(10, 0)) echo 'checked'; ?> /> <?php echo ucfirst(getAnswerDescription(10, 0)) ?> | 
                                    <input type="radio" name="r10" value="<?php echo getAnswerQuestionID(10, 1) ?>"  required <?php if (getUserAnswerAnswerID($user_ID, 10) == getAnswerQuestionID(10, 1)) echo 'checked'; ?> /> <?php echo ucfirst(getAnswerDescription(10, 1)) ?> 
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Hanseniase?</b>
                                <div class="optionYoN">
                                    <input type="radio" name="r11" value="<?php echo getAnswerQuestionID(11, 0) ?>"  required <?php if (getUserAnswerAnswerID($user_ID, 11) == getAnswerQuestionID(11, 0)) echo 'checked'; ?> /> <?php echo ucfirst(getAnswerDescription(11, 0)) ?> | 
                                    <input type="radio" name="r11" value="<?php echo getAnswerQuestionID(11, 1) ?>"  required <?php if (getUserAnswerAnswerID($user_ID, 11) == getAnswerQuestionID(11, 1)) echo 'checked'; ?> /> <?php echo ucfirst(getAnswerDescription(11, 1)) ?> 
                                </div>
                            </td>
                            <td>
                                <b>Cardiopatia?</b>
                                <div class="optionYoN">
                                    <input type="radio" name="r12" value="<?php echo getAnswerQuestionID(12, 0) ?>"  required <?php if (getUserAnswerAnswerID($user_ID, 12) == getAnswerQuestionID(12, 0)) echo 'checked'; ?> /> <?php echo ucfirst(getAnswerDescription(12, 0)) ?> | 
                                    <input type="radio" name="r12" value="<?php echo getAnswerQuestionID(12, 1) ?>"  required <?php if (getUserAnswerAnswerID($user_ID, 12) == getAnswerQuestionID(12, 1)) echo 'checked'; ?> /> <?php echo ucfirst(getAnswerDescription(12, 1)) ?> 
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Algum tipo de câncer?</b>
                                <div class="optionYoN">
                                    <input type="radio" name="r13" value="<?php echo getAnswerQuestionID(13, 0) ?>"  required <?php if (getUserAnswerAnswerID($user_ID, 13) == getAnswerQuestionID(13, 0)) echo 'checked'; ?> /> <?php echo ucfirst(getAnswerDescription(13, 0)) ?> | 
                                    <input type="radio" name="r13" value="<?php echo getAnswerQuestionID(13, 1) ?>"  required <?php if (getUserAnswerAnswerID($user_ID, 13) == getAnswerQuestionID(13, 1)) echo 'checked'; ?> /> <?php echo ucfirst(getAnswerDescription(13, 1)) ?> 
                                </div>
                            </td>
                            <td>
                                <b>Portador de marcapasso/pinos?</b>
                                <div class="optionYoN">
                                    <input type="radio" name="r14" value="<?php echo getAnswerQuestionID(14, 0) ?>"  required <?php if (getUserAnswerAnswerID($user_ID, 14) == getAnswerQuestionID(14, 0)) echo 'checked'; ?> /> <?php echo ucfirst(getAnswerDescription(14, 0)) ?> | 
                                    <input type="radio" name="r14" value="<?php echo getAnswerQuestionID(14, 1) ?>"  required <?php if (getUserAnswerAnswerID($user_ID, 14) == getAnswerQuestionID(14, 1)) echo 'checked'; ?> /> <?php echo ucfirst(getAnswerDescription(14, 1)) ?> 
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Distúrbio circulatório?</b>
                                <div class="optionYoN">
                                    <input type="radio" name="r15" value="<?php echo getAnswerQuestionID(15, 0) ?>"  required <?php if (getUserAnswerAnswerID($user_ID, 15) == getAnswerQuestionID(15, 0)) echo 'checked'; ?> /> <?php echo ucfirst(getAnswerDescription(15, 0)) ?> | 
                                    <input type="radio" name="r15" value="<?php echo getAnswerQuestionID(15, 1) ?>"  required <?php if (getUserAnswerAnswerID($user_ID, 15) == getAnswerQuestionID(15, 1)) echo 'checked'; ?> /> <?php echo ucfirst(getAnswerDescription(15, 1)) ?> 
                                </div>
                            </td>
                            <td>
                                <b>Hepatite?</b>
                                <div class="optionYoN">
                                    <input type="radio" name="r16" value="<?php echo getAnswerQuestionID(16, 0) ?>"  required <?php if (getUserAnswerAnswerID($user_ID, 16) == getAnswerQuestionID(16, 0)) echo 'checked'; ?> /> <?php echo ucfirst(getAnswerDescription(16, 0)) ?> | 
                                    <input type="radio" name="r16" value="<?php echo getAnswerQuestionID(16, 1) ?>"  required <?php if (getUserAnswerAnswerID($user_ID, 16) == getAnswerQuestionID(16, 1)) echo 'checked'; ?> /> <?php echo ucfirst(getAnswerDescription(16, 1)) ?> 
                                </div>
                            </td>
                        </tr>
                    </table>

                </div>
                <br>
                <br>
                <br>
                <center>
                    <?php setToken('tokenAnamnese') ?>
                    <input name="token" type="text" value="<?php echo addToken('tokenAnamnese') ?>" hidden/>
                    <button type="submit" class="enterButton">SALVAR</button>

                    <a href="print.php?data=<?php echo $user_ID ?>">
                        <input type="button" class="otherButton" value="Imprimir Ficha"/>
                    </a>
              </center>
            </form>
            <br>
        </div>

        <div id="plantContent">
            <?php
                $patologia = doListPathologys();
            ?>
            <form action="" METHOD="POST">
                <table width="100%" id="obs-table">
                    <tr>
                        <th colspan="5">
                            <b>
                                OBSERVAÇÕES PROFISSIONAIS:
                            </b>
                        </th>
                    </tr>
                    <tr>
                        <td colspan="7">
                            <div id="plantar">
                                <img src="layout\images\vp.png"/>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <b>
                                PATOLOGIAS PÉ ESQUERDO:
                            </b>
                        </th>
                        <th>
                            <b>
                                PATOLOGIAS PÉ DIREITO:
                            </b>
                        </th>
                    </tr>

                    <tr>   
                        <td>1
                            <select class="mediumSelectInp" name="patPE01">
                                <?php
                                    for($p = 1; $p <= count($patologia); ++$p) {
                                        if(getUserPathologyValue($user_ID, 1, 'VP') == $patologia[$p]['id'])
                                            echo '<option value="'.$patologia[$p]['id'].'" selected>'.strtoupper($patologia[$p]['nome']).'</option>';
                                        else
                                            echo '<option value="'.$patologia[$p]['id'].'">'.strtoupper($patologia[$p]['nome']).'</option>';
                                    }
                                    if(getUserPathologyValue($user_ID, 1, 'VP') != 0)
                                        echo '<option value="0">-- PATOLOGIA --</option>';
                                    else
                                        echo '<option value="0" selected>-- PATOLOGIA --</option>';
                                ?>
                            </select>
                            <select class="smallSelectInp" name="posPE01">
                                <?php
                                    for($p = 1; $p < 17; ++$p) {
                                        if(getUserPathologyPos($user_ID, 1, 'VP') == $p)
                                            echo '<option value="'.$p.'" selected>'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                        else
                                            echo '<option value="'.$p.'">'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                    }
                                    if(getUserPathologyPos($user_ID, 1, 'VP') != 0)
                                        echo '<option value="0">-- POS --</option>';
                                    else
                                        echo '<option value="0" selected>-- POS --</option>';
                                ?>
                            </select>
                        </td>
                        <td>1
                            <select class="mediumSelectInp" name="patPD01">
                                <?php
                                    for($p = 1; $p <= count($patologia); ++$p) {
                                        if(getUserPathologyValue($user_ID, 6, 'VP') == $patologia[$p]['id'])
                                            echo '<option value="'.$patologia[$p]['id'].'" selected>'.strtoupper($patologia[$p]['nome']).'</option>';
                                        else
                                            echo '<option value="'.$patologia[$p]['id'].'">'.strtoupper($patologia[$p]['nome']).'</option>';
                                    }
                                    if(getUserPathologyValue($user_ID, 6, 'VP') != 0)
                                        echo '<option value="0">-- PATOLOGIA --</option>';
                                    else
                                        echo '<option value="0" selected>-- PATOLOGIA --</option>';
                                ?>
                            </select>
                            <select class="smallSelectInp" name="posPD01">
                                <?php
                                    for($p = 1; $p < 17; ++$p) {
                                        if(getUserPathologyPos($user_ID, 6, 'VP') == $p)
                                            echo '<option value="'.$p.'" selected>'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                        else
                                            echo '<option value="'.$p.'">'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                    }
                                    if(getUserPathologyPos($user_ID, 6, 'VP') != 0)
                                        echo '<option value="0">-- POS --</option>';
                                    else
                                        echo '<option value="0" selected>-- POS --</option>';
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>   
                        <td>2
                            <select class="mediumSelectInp" name="patPE02">
                            <?php
                                    for($p = 1; $p <= count($patologia); ++$p) {
                                        if(getUserPathologyValue($user_ID, 2, 'VP') == $patologia[$p]['id'])
                                            echo '<option value="'.$patologia[$p]['id'].'" selected>'.strtoupper($patologia[$p]['nome']).'</option>';
                                        else
                                            echo '<option value="'.$patologia[$p]['id'].'">'.strtoupper($patologia[$p]['nome']).'</option>';
                                    }
                                    if(getUserPathologyValue($user_ID, 2, 'VP') != 0)
                                        echo '<option value="0">-- PATOLOGIA --</option>';
                                    else
                                        echo '<option value="0" selected>-- PATOLOGIA --</option>';
                            ?>
                            </select>
                            <select class="smallSelectInp" name="posPE02">
                                <?php
                                    for($p = 1; $p < 17; ++$p) {
                                        if(getUserPathologyPos($user_ID, 2, 'VP') == $p)
                                            echo '<option value="'.$p.'" selected>'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                        else
                                            echo '<option value="'.$p.'">'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                    }
                                    if(getUserPathologyPos($user_ID, 2, 'VP') != 0)
                                        echo '<option value="0">-- POS --</option>';
                                    else
                                        echo '<option value="0" selected>-- POS --</option>';
                                ?>
                            </select>
                        </td>
                        <td>2
                            <select class="mediumSelectInp" name="patPD02">
                            <?php
                                    for($p = 1; $p <= count($patologia); ++$p) {
                                        if(getUserPathologyValue($user_ID, 7, 'VP') == $patologia[$p]['id'])
                                            echo '<option value="'.$patologia[$p]['id'].'" selected>'.strtoupper($patologia[$p]['nome']).'</option>';
                                        else
                                            echo '<option value="'.$patologia[$p]['id'].'">'.strtoupper($patologia[$p]['nome']).'</option>';
                                    }
                                    if(getUserPathologyValue($user_ID, 7, 'VP') != 0)
                                        echo '<option value="0">-- PATOLOGIA --</option>';
                                    else
                                        echo '<option value="0" selected>-- PATOLOGIA --</option>';
                            ?>
                            </select>
                            <select class="smallSelectInp" name="posPD02">
                                <?php
                                    for($p = 1; $p < 17; ++$p) {
                                        if(getUserPathologyPos($user_ID, 7, 'VP') == $p)
                                            echo '<option value="'.$p.'" selected>'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                        else
                                            echo '<option value="'.$p.'">'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                    }
                                    if(getUserPathologyPos($user_ID, 7, 'VP') != 0)
                                        echo '<option value="0">-- POS --</option>';
                                    else
                                        echo '<option value="0" selected>-- POS --</option>';
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>   
                        <td>3
                            <select class="mediumSelectInp" name="patPE03">
                                <?php
                                    for($p = 1; $p <= count($patologia); ++$p) {
                                        if(getUserPathologyValue($user_ID, 3, 'VP') == $patologia[$p]['id'])
                                            echo '<option value="'.$patologia[$p]['id'].'" selected>'.strtoupper($patologia[$p]['nome']).'</option>';
                                        else
                                            echo '<option value="'.$patologia[$p]['id'].'">'.strtoupper($patologia[$p]['nome']).'</option>';
                                    }
                                    if(getUserPathologyValue($user_ID, 3, 'VP') != 0)
                                        echo '<option value="0">-- PATOLOGIA --</option>';
                                    else
                                        echo '<option value="0" selected>-- PATOLOGIA --</option>';
                                ?>
                            </select>
                            <select class="smallSelectInp" name="posPE03">
                                <?php
                                    for($p = 1; $p < 17; ++$p) {
                                        if(getUserPathologyPos($user_ID, 3, 'VP') == $p)
                                            echo '<option value="'.$p.'" selected>'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                        else
                                            echo '<option value="'.$p.'">'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                    }
                                    if(getUserPathologyPos($user_ID, 3, 'VP') != 0)
                                        echo '<option value="0">-- POS --</option>';
                                    else
                                        echo '<option value="0" selected>-- POS --</option>';
                                ?>
                            </select>
                        </td>
                        <td>3
                            <select class="mediumSelectInp" name="patPD03">
                                <?php
                                    for($p = 1; $p <= count($patologia); ++$p) {
                                        if(getUserPathologyValue($user_ID, 8, 'VP') == $patologia[$p]['id'])
                                            echo '<option value="'.$patologia[$p]['id'].'" selected>'.strtoupper($patologia[$p]['nome']).'</option>';
                                        else
                                            echo '<option value="'.$patologia[$p]['id'].'">'.strtoupper($patologia[$p]['nome']).'</option>';
                                    }
                                    if(getUserPathologyValue($user_ID, 8, 'VP') != 0)
                                        echo '<option value="0">-- PATOLOGIA --</option>';
                                    else
                                        echo '<option value="0" selected>-- PATOLOGIA --</option>';
                                ?>
                            </select>
                            <select class="smallSelectInp" name="posPD03">
                                <?php
                                    for($p = 1; $p < 17; ++$p) {
                                        if(getUserPathologyPos($user_ID, 8, 'VP') == $p)
                                            echo '<option value="'.$p.'" selected>'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                        else
                                            echo '<option value="'.$p.'">'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                    }
                                    if(getUserPathologyPos($user_ID, 8, 'VP') != 0)
                                        echo '<option value="0">-- POS --</option>';
                                    else
                                        echo '<option value="0" selected>-- POS --</option>';
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>   
                        <td>4
                            <select class="mediumSelectInp" name="patPE04">
                                <?php
                                    for($p = 1; $p <= count($patologia); ++$p) {
                                        if(getUserPathologyValue($user_ID, 4, 'VP') == $patologia[$p]['id'])
                                            echo '<option value="'.$patologia[$p]['id'].'" selected>'.strtoupper($patologia[$p]['nome']).'</option>';
                                        else
                                            echo '<option value="'.$patologia[$p]['id'].'">'.strtoupper($patologia[$p]['nome']).'</option>';
                                    }
                                    if(getUserPathologyValue($user_ID, 4, 'VP') != 0)
                                        echo '<option value="0">-- PATOLOGIA --</option>';
                                    else
                                        echo '<option value="0" selected>-- PATOLOGIA --</option>';
                                ?>
                            </select>
                            <select class="smallSelectInp" name="posPE04">
                                <?php
                                    for($p = 1; $p < 17; ++$p) {
                                        if(getUserPathologyPos($user_ID, 4, 'VP') == $p)
                                            echo '<option value="'.$p.'" selected>'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                        else
                                            echo '<option value="'.$p.'">'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                    }
                                    if(getUserPathologyPos($user_ID, 4, 'VP') != 0)
                                        echo '<option value="0">-- POS --</option>';
                                    else
                                        echo '<option value="0" selected>-- POS --</option>';
                                ?>
                            </select>
                        </td>
                        <td>4
                            <select class="mediumSelectInp" name="patPD04">
                            <?php
                                    for($p = 1; $p <= count($patologia); ++$p) {
                                        if(getUserPathologyValue($user_ID, 9, 'VP') == $patologia[$p]['id'])
                                            echo '<option value="'.$patologia[$p]['id'].'" selected>'.strtoupper($patologia[$p]['nome']).'</option>';
                                        else
                                            echo '<option value="'.$patologia[$p]['id'].'">'.strtoupper($patologia[$p]['nome']).'</option>';
                                    }
                                    if(getUserPathologyValue($user_ID, 9, 'VP') != 0)
                                        echo '<option value="0">-- PATOLOGIA --</option>';
                                    else
                                        echo '<option value="0" selected>-- PATOLOGIA --</option>';
                            ?>
                            </select>
                            <select class="smallSelectInp" name="posPD04">
                                <?php
                                    for($p = 1; $p < 17; ++$p) {
                                        if(getUserPathologyPos($user_ID, 9, 'VP') == $p)
                                            echo '<option value="'.$p.'" selected>'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                        else
                                            echo '<option value="'.$p.'">'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                    }
                                    if(getUserPathologyPos($user_ID, 9, 'VP') != 0)
                                        echo '<option value="0">-- POS --</option>';
                                    else
                                        echo '<option value="0" selected>-- POS --</option>';
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>   
                        <td>5
                            <select class="mediumSelectInp" name="patPE05">
                            <?php
                                    for($p = 1; $p <= count($patologia); ++$p) {
                                        if(getUserPathologyValue($user_ID, 5, 'VP') == $patologia[$p]['id'])
                                            echo '<option value="'.$patologia[$p]['id'].'" selected>'.strtoupper($patologia[$p]['nome']).'</option>';
                                        else
                                            echo '<option value="'.$patologia[$p]['id'].'">'.strtoupper($patologia[$p]['nome']).'</option>';
                                    }
                                    if(getUserPathologyValue($user_ID, 5, 'VP') != 0)
                                        echo '<option value="0">-- PATOLOGIA --</option>';
                                    else
                                        echo '<option value="0" selected>-- PATOLOGIA --</option>';
                            ?>
                            </select>
                            <select class="smallSelectInp" name="posPE05">
                                <?php
                                    for($p = 1; $p < 17; ++$p) {
                                        if(getUserPathologyPos($user_ID, 5, 'VP') == $p)
                                            echo '<option value="'.$p.'" selected>'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                        else
                                            echo '<option value="'.$p.'">'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                    }
                                    if(getUserPathologyPos($user_ID, 5, 'VP') != 0)
                                        echo '<option value="0">-- POS --</option>';
                                    else
                                        echo '<option value="0" selected>-- POS --</option>';
                                ?>
                            </select>
                        </td>
                        <td>5
                            <select class="mediumSelectInp" name="patPD05">
                                <?php
                                    for($p = 1; $p <= count($patologia); ++$p) {
                                        if(getUserPathologyValue($user_ID, 10, 'VP') == $patologia[$p]['id'])
                                            echo '<option value="'.$patologia[$p]['id'].'" selected>'.strtoupper($patologia[$p]['nome']).'</option>';
                                        else
                                            echo '<option value="'.$patologia[$p]['id'].'">'.strtoupper($patologia[$p]['nome']).'</option>';
                                    }
                                    if(getUserPathologyValue($user_ID, 10, 'VP') != 0)
                                        echo '<option value="0">-- PATOLOGIA --</option>';
                                    else
                                        echo '<option value="0" selected>-- PATOLOGIA --</option>';
                                ?>
                            </select>
                            <select class="smallSelectInp" name="posPD05">
                                <?php
                                    for($p = 1; $p < 17; ++$p) {
                                        if(getUserPathologyPos($user_ID, 10, 'VP') == $p)
                                            echo '<option value="'.$p.'" selected>'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                        else
                                            echo '<option value="'.$p.'">'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                    }
                                    if(getUserPathologyPos($user_ID, 10, 'VP') != 0)
                                        echo '<option value="0">-- POS --</option>';
                                    else
                                        echo '<option value="0" selected>-- POS --</option>';
                                ?>
                            </select>
                        </td>
                    </tr>
                </table>

                <br>
                <br>
                <br>
                <center>
                    <?php setToken('tokenPlant') ?>
                    <input name="token" type="text" value="<?php echo addToken('tokenPlant') ?>" hidden/>
                    <button type="submit" class="enterButton">SALVAR</button>
                </center>
            </form>
        </div>

        <div id="dorsalContent">
            <?php
                $patologia = doListPathologys();
            ?>
            <form action="" METHOD="POST">
                <table width="100%" id="obs-table">
                    <tr>
                        <th colspan="5">
                            <b>
                                OBSERVAÇÕES PROFISSIONAIS:
                            </b>
                        </th>
                    </tr>
                    <tr>
                        <td colspan="7">
                            <div id="plantar">
                                <img src="layout\images\vd.png"/>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <b>
                                PATOLOGIAS PÉ ESQUERDO:
                            </b>
                        </th>
                        <th>
                            <b>
                                PATOLOGIAS PÉ DIREITO:
                            </b>
                        </th>
                    </tr>

                    <tr>   
                        <td>1
                            <select class="mediumSelectInp" name="patPE01">
                                <?php
                                    for($p = 1; $p <= count($patologia); ++$p) {
                                        if(getUserPathologyValue($user_ID, 1, 'VD') == $patologia[$p]['id'])
                                            echo '<option value="'.$patologia[$p]['id'].'" selected>'.strtoupper($patologia[$p]['nome']).'</option>';
                                        else
                                            echo '<option value="'.$patologia[$p]['id'].'">'.strtoupper($patologia[$p]['nome']).'</option>';
                                    }
                                    if(getUserPathologyValue($user_ID, 1, 'VD') != 0)
                                        echo '<option value="0">-- PATOLOGIA --</option>';
                                    else
                                        echo '<option value="0" selected>-- PATOLOGIA --</option>';
                                ?>
                            </select>
                            <select class="smallSelectInp" name="posPE01">
                                <?php
                                    for($p = 1; $p < 17; ++$p) {
                                        if(getUserPathologyPos($user_ID, 1, 'VD') == $p)
                                            echo '<option value="'.$p.'" selected>'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                        else
                                            echo '<option value="'.$p.'">'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                    }
                                    if(getUserPathologyPos($user_ID, 1, 'VD') != 0)
                                        echo '<option value="0">-- POS --</option>';
                                    else
                                        echo '<option value="0" selected>-- POS --</option>';
                                ?>
                            </select>
                        </td>
                        <td>1
                            <select class="mediumSelectInp" name="patPD01">
                                <?php
                                    for($p = 1; $p <= count($patologia); ++$p) {
                                        if(getUserPathologyValue($user_ID, 6, 'VD') == $patologia[$p]['id'])
                                            echo '<option value="'.$patologia[$p]['id'].'" selected>'.strtoupper($patologia[$p]['nome']).'</option>';
                                        else
                                            echo '<option value="'.$patologia[$p]['id'].'">'.strtoupper($patologia[$p]['nome']).'</option>';
                                    }
                                    if(getUserPathologyValue($user_ID, 6, 'VD') != 0)
                                        echo '<option value="0">-- PATOLOGIA --</option>';
                                    else
                                        echo '<option value="0" selected>-- PATOLOGIA --</option>';
                                ?>
                            </select>
                            <select class="smallSelectInp" name="posPD01">
                                <?php
                                    for($p = 1; $p < 17; ++$p) {
                                        if(getUserPathologyPos($user_ID, 6, 'VD') == $p)
                                            echo '<option value="'.$p.'" selected>'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                        else
                                            echo '<option value="'.$p.'">'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                    }
                                    if(getUserPathologyPos($user_ID, 6, 'VD') != 0)
                                        echo '<option value="0">-- POS --</option>';
                                    else
                                        echo '<option value="0" selected>-- POS --</option>';
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>   
                        <td>2
                            <select class="mediumSelectInp" name="patPE02">
                            <?php
                                    for($p = 1; $p <= count($patologia); ++$p) {
                                        if(getUserPathologyValue($user_ID, 2, 'VD') == $patologia[$p]['id'])
                                            echo '<option value="'.$patologia[$p]['id'].'" selected>'.strtoupper($patologia[$p]['nome']).'</option>';
                                        else
                                            echo '<option value="'.$patologia[$p]['id'].'">'.strtoupper($patologia[$p]['nome']).'</option>';
                                    }
                                    if(getUserPathologyValue($user_ID, 2, 'VD') != 0)
                                        echo '<option value="0">-- PATOLOGIA --</option>';
                                    else
                                        echo '<option value="0" selected>-- PATOLOGIA --</option>';
                            ?>
                            </select>
                            <select class="smallSelectInp" name="posPE02">
                                <?php
                                    for($p = 1; $p < 17; ++$p) {
                                        if(getUserPathologyPos($user_ID, 2, 'VD') == $p)
                                            echo '<option value="'.$p.'" selected>'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                        else
                                            echo '<option value="'.$p.'">'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                    }
                                    if(getUserPathologyPos($user_ID, 2, 'VD') != 0)
                                        echo '<option value="0">-- POS --</option>';
                                    else
                                        echo '<option value="0" selected>-- POS --</option>';
                                ?>
                            </select>
                        </td>
                        <td>2
                            <select class="mediumSelectInp" name="patPD02">
                            <?php
                                    for($p = 1; $p <= count($patologia); ++$p) {
                                        if(getUserPathologyValue($user_ID, 7, 'VD') == $patologia[$p]['id'])
                                            echo '<option value="'.$patologia[$p]['id'].'" selected>'.strtoupper($patologia[$p]['nome']).'</option>';
                                        else
                                            echo '<option value="'.$patologia[$p]['id'].'">'.strtoupper($patologia[$p]['nome']).'</option>';
                                    }
                                    if(getUserPathologyValue($user_ID, 7, 'VD') != 0)
                                        echo '<option value="0">-- PATOLOGIA --</option>';
                                    else
                                        echo '<option value="0" selected>-- PATOLOGIA --</option>';
                            ?>
                            </select>
                            <select class="smallSelectInp" name="posPD02">
                                <?php
                                    for($p = 1; $p < 17; ++$p) {
                                        if(getUserPathologyPos($user_ID, 7, 'VD') == $p)
                                            echo '<option value="'.$p.'" selected>'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                        else
                                            echo '<option value="'.$p.'">'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                    }
                                    if(getUserPathologyPos($user_ID, 7, 'VD') != 0)
                                        echo '<option value="0">-- POS --</option>';
                                    else
                                        echo '<option value="0" selected>-- POS --</option>';
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>   
                        <td>3
                            <select class="mediumSelectInp" name="patPE03">
                                <?php
                                    for($p = 1; $p <= count($patologia); ++$p) {
                                        if(getUserPathologyValue($user_ID, 3, 'VD') == $patologia[$p]['id'])
                                            echo '<option value="'.$patologia[$p]['id'].'" selected>'.strtoupper($patologia[$p]['nome']).'</option>';
                                        else
                                            echo '<option value="'.$patologia[$p]['id'].'">'.strtoupper($patologia[$p]['nome']).'</option>';
                                    }
                                    if(getUserPathologyValue($user_ID, 3, 'VD') != 0)
                                        echo '<option value="0">-- PATOLOGIA --</option>';
                                    else
                                        echo '<option value="0" selected>-- PATOLOGIA --</option>';
                                ?>
                            </select>
                            <select class="smallSelectInp" name="posPE03">
                                <?php
                                    for($p = 1; $p < 17; ++$p) {
                                        if(getUserPathologyPos($user_ID, 3, 'VD') == $p)
                                            echo '<option value="'.$p.'" selected>'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                        else
                                            echo '<option value="'.$p.'">'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                    }
                                    if(getUserPathologyPos($user_ID, 3, 'VD') != 0)
                                        echo '<option value="0">-- POS --</option>';
                                    else
                                        echo '<option value="0" selected>-- POS --</option>';
                                ?>
                            </select>
                        </td>
                        <td>3
                            <select class="mediumSelectInp" name="patPD03">
                                <?php
                                    for($p = 1; $p <= count($patologia); ++$p) {
                                        if(getUserPathologyValue($user_ID, 8, 'VD') == $patologia[$p]['id'])
                                            echo '<option value="'.$patologia[$p]['id'].'" selected>'.strtoupper($patologia[$p]['nome']).'</option>';
                                        else
                                            echo '<option value="'.$patologia[$p]['id'].'">'.strtoupper($patologia[$p]['nome']).'</option>';
                                    }
                                    if(getUserPathologyValue($user_ID, 8, 'VD') != 0)
                                        echo '<option value="0">-- PATOLOGIA --</option>';
                                    else
                                        echo '<option value="0" selected>-- PATOLOGIA --</option>';
                                ?>
                            </select>
                            <select class="smallSelectInp" name="posPD03">
                                <?php
                                    for($p = 1; $p < 17; ++$p) {
                                        if(getUserPathologyPos($user_ID, 8, 'VD') == $p)
                                            echo '<option value="'.$p.'" selected>'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                        else
                                            echo '<option value="'.$p.'">'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                    }
                                    if(getUserPathologyPos($user_ID, 8, 'VD') != 0)
                                        echo '<option value="0">-- POS --</option>';
                                    else
                                        echo '<option value="0" selected>-- POS --</option>';
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>   
                        <td>4
                            <select class="mediumSelectInp" name="patPE04">
                                <?php
                                    for($p = 1; $p <= count($patologia); ++$p) {
                                        if(getUserPathologyValue($user_ID, 4, 'VD') == $patologia[$p]['id'])
                                            echo '<option value="'.$patologia[$p]['id'].'" selected>'.strtoupper($patologia[$p]['nome']).'</option>';
                                        else
                                            echo '<option value="'.$patologia[$p]['id'].'">'.strtoupper($patologia[$p]['nome']).'</option>';
                                    }
                                    if(getUserPathologyValue($user_ID, 4, 'VD') != 0)
                                        echo '<option value="0">-- PATOLOGIA --</option>';
                                    else
                                        echo '<option value="0" selected>-- PATOLOGIA --</option>';
                                ?>
                            </select>
                            <select class="smallSelectInp" name="posPE04">
                                <?php
                                    for($p = 1; $p < 17; ++$p) {
                                        if(getUserPathologyPos($user_ID, 4, 'VD') == $p)
                                            echo '<option value="'.$p.'" selected>'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                        else
                                            echo '<option value="'.$p.'">'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                    }
                                    if(getUserPathologyPos($user_ID, 4, 'VD') != 0)
                                        echo '<option value="0">-- POS --</option>';
                                    else
                                        echo '<option value="0" selected>-- POS --</option>';
                                ?>
                            </select>
                        </td>
                        <td>4
                            <select class="mediumSelectInp" name="patPD04">
                            <?php
                                    for($p = 1; $p <= count($patologia); ++$p) {
                                        if(getUserPathologyValue($user_ID, 9, 'VD') == $patologia[$p]['id'])
                                            echo '<option value="'.$patologia[$p]['id'].'" selected>'.strtoupper($patologia[$p]['nome']).'</option>';
                                        else
                                            echo '<option value="'.$patologia[$p]['id'].'">'.strtoupper($patologia[$p]['nome']).'</option>';
                                    }
                                    if(getUserPathologyValue($user_ID, 9, 'VD') != 0)
                                        echo '<option value="0">-- PATOLOGIA --</option>';
                                    else
                                        echo '<option value="0" selected>-- PATOLOGIA --</option>';
                            ?>
                            </select>
                            <select class="smallSelectInp" name="posPD04">
                                <?php
                                    for($p = 1; $p < 17; ++$p) {
                                        if(getUserPathologyPos($user_ID, 9, 'VD') == $p)
                                            echo '<option value="'.$p.'" selected>'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                        else
                                            echo '<option value="'.$p.'">'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                    }
                                    if(getUserPathologyPos($user_ID, 9, 'VD') != 0)
                                        echo '<option value="0">-- POS --</option>';
                                    else
                                        echo '<option value="0" selected>-- POS --</option>';
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>   
                        <td>5
                            <select class="mediumSelectInp" name="patPE05">
                            <?php
                                    for($p = 1; $p <= count($patologia); ++$p) {
                                        if(getUserPathologyValue($user_ID, 5, 'VD') == $patologia[$p]['id'])
                                            echo '<option value="'.$patologia[$p]['id'].'" selected>'.strtoupper($patologia[$p]['nome']).'</option>';
                                        else
                                            echo '<option value="'.$patologia[$p]['id'].'">'.strtoupper($patologia[$p]['nome']).'</option>';
                                    }
                                    if(getUserPathologyValue($user_ID, 5, 'VD') != 0)
                                        echo '<option value="0">-- PATOLOGIA --</option>';
                                    else
                                        echo '<option value="0" selected>-- PATOLOGIA --</option>';
                            ?>
                            </select>
                            <select class="smallSelectInp" name="posPE05">
                                <?php
                                    for($p = 1; $p < 17; ++$p) {
                                        if(getUserPathologyPos($user_ID, 5, 'VD') == $p)
                                            echo '<option value="'.$p.'" selected>'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                        else
                                            echo '<option value="'.$p.'">'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                    }
                                    if(getUserPathologyPos($user_ID, 5, 'VD') != 0)
                                        echo '<option value="0">-- POS --</option>';
                                    else
                                        echo '<option value="0" selected>-- POS --</option>';
                                ?>
                            </select>
                        </td>
                        <td>5
                            <select class="mediumSelectInp" name="patPD05">
                                <?php
                                    for($p = 1; $p <= count($patologia); ++$p) {
                                        if(getUserPathologyValue($user_ID, 10, 'VD') == $patologia[$p]['id'])
                                            echo '<option value="'.$patologia[$p]['id'].'" selected>'.strtoupper($patologia[$p]['nome']).'</option>';
                                        else
                                            echo '<option value="'.$patologia[$p]['id'].'">'.strtoupper($patologia[$p]['nome']).'</option>';
                                    }
                                    if(getUserPathologyValue($user_ID, 10, 'VD') != 0)
                                        echo '<option value="0">-- PATOLOGIA --</option>';
                                    else
                                        echo '<option value="0" selected>-- PATOLOGIA --</option>';
                                ?>
                            </select>
                            <select class="smallSelectInp" name="posPD05">
                                <?php
                                    for($p = 1; $p < 17; ++$p) {
                                        if(getUserPathologyPos($user_ID, 10, 'VD') == $p)
                                            echo '<option value="'.$p.'" selected>'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                        else
                                            echo '<option value="'.$p.'">'.str_pad($p, 2, 0, STR_PAD_LEFT).'</option>';
                                    }
                                    if(getUserPathologyPos($user_ID, 10, 'VD') != 0)
                                        echo '<option value="0">-- POS --</option>';
                                    else
                                        echo '<option value="0" selected>-- POS --</option>';
                                ?>
                            </select>
                        </td>
                    </tr>
                </table>

                <br>
                <br>
                <br>
                <center>
                    <?php setToken('tokenDorsal') ?>
                    <input name="token" type="text" value="<?php echo addToken('tokenDorsal') ?>" hidden/>
                    <button type="submit" class="enterButton">SALVAR</button>
                </center>
            </form>
        </div>

        <div id="schedulingContent">
            
            <br/><br/>
            <div id="bt-Schedulling"> 
                <center>
                    <form action="uSchedulingAdd.php?data=<?php echo $user_ID ?>" method="POST">
                        <button type="submit" class="enterButton">novo agendamento</button>
                    </form>
                </center>
            </div>
            <br/><br/>

            <center>
                <?php
                    $apage = isset($_GET['page']) ? $_GET['page'] : 1;
                    $rowsPerPage = $config['rowsPerPage'];
                    $ppage = ($apage * $rowsPerPage) - $rowsPerPage;

                    if(!isset($_GET['order'])) $_GET['order'] = 'desc';
                    
                    $result = mysql_select_multi("SELECT `id` FROM `agendamentos` WHERE `usuario_id` = '".$user_ID."';");
                    
                    if($result !== false) {
                        $total = count($result);
                        $countLink = ceil($total / $rowsPerPage);
                        for ($i = $apage - 3; $i <= $countLink; ++$i) {
                            
                            if($i < 1) $i = 1;
                            
                            $type = (isset($_GET['type'])) ? '&type='.$_GET['type'] : '';
                            $order = (isset($_GET['order'])) ? '&order='.$_GET['order'] : '';

                            if($i == $apage)
                                echo ' ['. $i . '] ';
                            else
                                echo '<a href="userViewer.php?data='.$user_ID.'&scheduleAlter&page='.$i.$type.$order.'">[ '.$i.' ]</a> ';
                        }
                    }
                ?>
            </center>
            <br/><br/>
            <div id="sc-content">
                <table class="sc-table">
                    <tr>
                        <th onclick="location.href='userViewer.php?data=<?php echo $user_ID; ?>&scheduleAlter&page=<?php echo $apage; ?>&order=<?php echo getOrder($_GET['order']); ?>'">Nº</th>
                        <th onclick="location.href='userViewer.php?data=<?php echo $user_ID; ?>&scheduleAlter&page=<?php echo $apage; ?>&type=1&order=<?php echo getOrder($_GET['order']); ?>'">Data do Agendamento</th>
                        <th onclick="location.href='userViewer.php?data=<?php echo $user_ID; ?>&scheduleAlter&page=<?php echo $apage; ?>&type=2&order=<?php echo getOrder($_GET['order']); ?>'">Data do Atendimento</th>
                        <th onclick="location.href='userViewer.php?data=<?php echo $user_ID; ?>&scheduleAlter&page=<?php echo $apage; ?>&type=3&order=<?php echo getOrder($_GET['order']); ?>'">STATUS</th>
                    </tr>
                <?php 

                    if(isset($_GET['type']) && $_GET['type'] == 1)
                        $query = mysql_select_multi("SELECT `id` FROM `agendamentos` WHERE `usuario_id` = '".$user_ID."' ORDER by `data_agendamento` ".getOrder($_GET['order'])." LIMIT $ppage, $rowsPerPage;");
                    elseif(isset($_GET['type']) && $_GET['type'] == 2)
                        $query = mysql_select_multi("SELECT `id` FROM `agendamentos` WHERE `usuario_id` = '".$user_ID."' ORDER by `data_agendada` ".getOrder($_GET['order'])." LIMIT $ppage, $rowsPerPage;");
                    elseif(isset($_GET['type']) && $_GET['type'] == 3)
                        $query = mysql_select_multi("SELECT `id` FROM `agendamentos` WHERE `usuario_id` = '".$user_ID."' ORDER by `status` ".getOrder($_GET['order'])." LIMIT $ppage, $rowsPerPage;");
                    else
                        $query = mysql_select_multi("SELECT `id` FROM `agendamentos` WHERE `usuario_id` = '".$user_ID."' ORDER by id ".getOrder($_GET['order'])." LIMIT $ppage, $rowsPerPage;");

                    if($query) {
                        foreach($query as $key => $value) {
                            $scheduled_ID = $value['id'];
                    ?>
                        <tr <?php if(isSchedulingServicePerformed($scheduled_ID)) {?> style="background-color: rgb(85 192 221);" <?php } ?> onclick="location.href='uSchedulingEdit.php?data=<?php echo $user_ID ?>&scheduling=<?php echo $scheduled_ID ?>'">
                            <td><?php echo $scheduled_ID ?></td>
                            <td><?php echo getSchedulingDateCreated($scheduled_ID) ?></td>
                            <td><?php echo getSchedulingDateScheduled($scheduled_ID).' - '.getSchedulingTimeScheduled($scheduled_ID).' as '.doTimeConvert(getSchedulingTimeScheduled($scheduled_ID), getTimeTotalService($scheduled_ID, true), true) ?></td>
                            <td><?php if(isSchedulingServicePerformed($scheduled_ID) !== false) echo 'ATENDIMENTO EFETUADO'; else echo 'AGUARDANDO ATENDIMENTO'; ?></td>
                        </tr>
                    <?php
                        }
                    }
                ?>
                
                </table>
            </div>
        </div>
<?php
}
include 'layout/overall/footer.php';
?>