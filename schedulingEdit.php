<?php
    require_once 'engine/init.php';
    doProtect();
    getPageAccess($user_data['id'], 2);
    $titlepage = "Controle de Usuário";
    include 'layout/overall/header.php';
    $schedule_ID = $_GET['scheduling'];
    $user_ID = $_GET['data'];
    $pay_ID = getPaymentID($schedule_ID);
    $attendance_ID = getAttendanceID($schedule_ID);

        /*********************************************************
        **
        ** VALIDAÇÃO QUE O ATENDIMENTO FOI REALIZADO COM SUCESSO
        **
        **********************************************************/

        if (empty($_POST) === false && getToken('tokenConfirmSchedule') && isSchedulingServicePerformed($_POST['schedule_id']) === false) {
            if(getExistTypePay($_POST['pay_type']) === false) {
                $errors[] = 'Houve um erro ao identificar o tipo de pagamento, se o erro persistir informe o administrador.';
            }
			
			if(getCashierOpeningExist() === false) {
                $errors[] = 'Você não pode confirmar o atendimento, com o caixa fechado.';				
			}
			
            if(date("Y-m-d H:i:s") < getSchedulingDateScheduled($_POST['schedule_id']).' '.getSchedulingTimeScheduled($_POST['schedule_id'])) {
                $errors[] = "Você não pode confirmar um atendimento antes da hora marcada.";
            }
			
			
			
			
			
			
			$files = $_FILES['file'];
			$f = 0;
            $fTypes = array('image/png', 'image/jpg', 'image/jpeg');
			foreach($files['error'] as $value) {
				if ($value != 4) {
					list($width, $height) = getimagesize($files['tmp_name'][$f]);
					if($files['size'][$f] > 0) {
						// Valida se o tipo é valido
						if(array_search($files['type'][$f], $fTypes) !== false) {
							if($width > config('picture')['width'] && $height > config('picture')['height']) {
								$errors[] = "Essa imagem que você enviou ultrapassou o tamanho máximo de largura ".config('picture')['width']."px - altura ".config('picture')['width']."px.";
							}
						} else {
							$errors[] = "O formato do arquivo enviado é invalido, você deve enviar um formato valido. Formatos valido: PNG | JPG | JPEG.";
						}
					} else {
						$errors[] = "O tamanho do arquivo está invalido.";
					}					
				}
				++$f;
			}
        }

        
        /*********************************************************
        **
        ** SE A VALIDAÇÃO DE ATENDIMENTO OCORREU SEM ERROS
        **
        **********************************************************/
        if (empty($_POST) === false && getToken('tokenConfirmSchedule') && empty($errors) && isSchedulingServicePerformed($_POST['schedule_id']) === false) {
            $register_data = array(
                'schedule_id' => $_POST['schedule_id'],
                'observacao' => empty($_POST['observacao']) ? '' : $_POST['observacao'],
                'image' => empty($_FILES['file']) ? false : $_FILES['file'],
                'valor' => $_POST['pay_value'],
                'pay_type' => $_POST['pay_type']
            );

            $msg[] = "Status do agendamento alterado com sucesso!!";
            $msg[] = "Foi encaminhado uma mensagem para o telefone cadastrado, informando a data e horário do agendamento.";
            $msg = implode('<br/>', $msg);
            doConfirmService($register_data);
            echo output_msgs($msg);
        }




        
        /*********************************************************
        **
        ** se VALIDAÇÃO QUE O ATENDIMENTO FOI CANCELADO COM
        ** SUCESSO
        **********************************************************/

        if (empty($_POST) === false && getToken('tokenCancelSchedule') && empty($errors) && isSchedulingServicePerformed($_POST['schedule_id']) === true) {
            $schedule_id = $_POST['schedule_id'];
            $msg = "Agendamento reiniciado!!";
            doRestartScheduling($schedule_id);
            echo output_msgs($msg);
        }
        
        /*********************************************************
        **
        ** VALIDA SE O USUARIO QUER DELETAR O AGENDAMENTO
        ** SE ELE DISSE QUE SIM É DELETADO SE NÃO É CANCELADO
        **********************************************************/
        if (empty($_POST) === false && getToken('tokenConfirmDeleteSchedule') && empty($errors) && ($_GET["del"] === 'true')) {
            doDeleteScheduling($_POST['schedule_id']);
            echo output_msgs("O agendamento foi deletado com sucesso!!");
            header("refresh: 2, scheduling.php");
        } 
        elseif (empty($_POST) === false && getToken('tokenDeleteSchedule') && empty($errors) && ($_GET["del"])) {
            setToken('tokenConfirmDeleteSchedule');
            echo output_warning("Você está prestes a deletar esse agendamento, assim como todos os dados que interligam a ele, você tem certeza disso?<br/>
            <form action='scheduling.php?subpage=schedulingEdit&data=".$_GET['data']."&scheduling=".$_GET['scheduling']."&del=true' method='POST'>
                <input name='schedule_id' type='text' value='".$_GET['scheduling']."' hidden/>
                <input name='token' type='text' value='".addToken('tokenConfirmDeleteSchedule')."' hidden/><br/>
                <button class='enterButton' type='submit'>CONFIRMAR</button>
                <a href='scheduling.php?subpage=schedulingEdit&data=".$_GET['data']."&scheduling=".$_GET['scheduling']."'>
                    <button class='backButton' type='button'>CANCELAR</button>
                </a>
            </form>
            ");
        }

        
        /*********************************************************
        **
        ** SE ALGUMA VALIDAÇÃO OCORREU COM ALGUM ERRO
        **
        **********************************************************/
        if (empty($errors) === false) {
            header("HTTP/1.1 401 Not Found");
            echo output_errors($errors);
        }


        $query = mysql_select_single("SELECT `id`, `usuario_id`, `data_agendamento`, `data_agendada`, `hora_agendada` FROM `agendamentos` where `id`= '".$_GET['scheduling']."';");

        if($query) {
            $serviceAgent = getScheduledServices($query['id']);
            $ar_query = mysql_select_single("SELECT `id`, `data_confirmacao`, `observacao` FROM `atendimentos_realizado` where `agendamento_id`= '".$query['id']."';");
            $pay_confirm = mysql_select_single("SELECT `id`, `valor_total`, `tipo_pagamento`, `data_pagamento`, `hora_pagamento` FROM `pagamentos` where `agendamento_id`= '".$query['id']."';");
            $valuePay = ($pay_confirm !== false) ? $pay_confirm['valor_total'] : getValueTotalService($query['id']);
            $typePay = ($pay_confirm !== false) ? $pay_confirm['tipo_pagamento'] : 1;
            $obs = (empty($ar_query['observacao'])) ? '' : $ar_query['observacao'];
            $disabled = (isSchedulingServicePerformed($query['id'])) ? 'disabled' : '';
            $hidden = (isSchedulingServicePerformed($query['id'])) ? 'hidden' : '';
?>
        <table class="generic-table">
            
            <form action="" method="POST" enctype='multipart/form-data'>
    
                <tr>
                    <th colspan="3">DADOS DE AGENDAMENTO</th>
                </tr>
                <tr>
                    <td class="generic-tExclusion" colspan="2">Data do Agendamento:</td>
                    <td colspan="2"><?php echo getSchedulingDateCreated($schedule_ID)?></td>
                </tr>
                <tr>
                    <td class="generic-tExclusion" colspan="2">Data do Atendimento:</td>
                    <td colspan="2"><?php echo getSchedulingDateScheduled($schedule_ID) ?> </td>
                </tr>
                <tr>
                    <td class="generic-tExclusion" colspan="2">Previsão de Inicio:</td>
                    <td colspan="2"><?php echo getSchedulingTimeScheduled($schedule_ID) ?></td>
                </tr>
                <tr>
                    <td class="generic-tExclusion" colspan="2">Previsão de Término:</td>
                    <td colspan="2"><?php echo doTimeConvert(getSchedulingTimeScheduled($schedule_ID), getTimeTotalService($schedule_ID, true), true); ?></td>
                </tr>
                <tr>
                    <th colspan="3">SERVIÇO AGENDADO</th>
                </tr>
                <tr class="generic-tExclusion">
                    <td colspan="3" style="text-align: center;">OBSERVAÇÃO</td>
                </tr>
                <tr class="generic-tExclusion">
                    <td colspan="3">
                        <textarea name="observacao" <?php echo $disabled ?> style="height: 50px; width: 100%; resize: none"><?php echo getAttendanceDescription(getAttendanceID($schedule_ID)) ?></textarea>
                    </td>
                </tr>
                <tr class="generic-tExclusion">
                    <td colspan="3" style="text-align: center;">Enviar arquivo(s)</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center;">
                        <div id="imageContent">
                            <?php
                                if(isAttendanceExist($attendance_ID))
                                   $img_query = mysql_select_multi("SELECT `id`, `img` FROM `atendimento_image` WHERE `atendimento_id` = '".$attendance_ID."'");
                                else 
                                    $img_query = false;
    
                                if($img_query) {
                                    foreach($img_query as $key => $value) {
                            ?>
                            <!-- JAVA SCRIPT -->
                            <script>
                                function closeUBox(){
                                    document.querySelectorAll('.imgs').forEach(ele => ele.classList.remove('imgSelect'));
                                }
    
                                function activeClass(cla, test) {
                                    document.querySelectorAll('.imgs').forEach(ele => ele.classList.remove('imgSelect'));
                                    document.querySelectorAll('.imgDisplay').forEach(el => el.classList.toggle('imgSelected', el === cla));
                                    document.querySelectorAll('.'+test).forEach(ele => ele.classList.add('imgSelect'));
                                }
                                                
                            </script>
                                        <div class="imageBox">
                                                <img class="imgDisplay" onclick="activeClass(this,'img_<?php echo $value['id'] ?>')" src="<?php echo getUserFolderForIMG(getUserCPF($user_ID)); ?>/<?php echo $schedule_ID ?>/<?php echo $value['img'] ?>">
                                            <a href="uSchedulingEdit.php?data=<?php echo $_GET['data'] ?>&scheduling=<?php echo $_GET['scheduling'] ?>&image=<?php echo $value['id'] ?>&del">
                                                <button type="button" class="backButton" <?php echo $hidden ?>>DELETAR</button>
                                            </a>
                                        </div>
                                        <link rel="stylesheet" type="text/css" href="layout/css/boxs.css"/>
                                        <div class="imgs img_<?php echo $value['id'] ?>" id="U-boxBody">
                                            <div id="U-boxMain">
                                                <button type="button" onclick="closeUBox()" id="U-boxButton">X</button>
                                                <div id="U-boxTitle">
                                                    <label class="U-title">IMAGE</label>
                                                </div>
                                                <div id="U-borderContent">
                                                    <div id="U-boxContent">
                                                        <img src="<?php echo getUserFolderForIMG(getUserCPF($user_ID))?>/<?php echo $schedule_ID ?>/<?php echo $value['img'] ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                            if(isset($_GET['image']) && $_GET['image'] == $value['id'] && isset($_GET['del'])) {
                                                if($value['img'] !== '') {
                                                    unlink(getUserFolderForIMG(getUserCPF($user_ID)).'/'.$schedule_ID.'/'.$value['img']);
                                                }
                                                mysql_delete("DELETE FROM `atendimento_image` WHERE `id`='".$value['id']."'");
                                                echo output_msgs("Imagem foi deletada com sucesso!!", "uSchedulingEdit.php?data=".$_GET['data']."&scheduling=".$_GET['scheduling']."");
                                            }
                                    }
                                }
                            ?>
                        </div>
                        <input type="file" name="file[]" id="file" multiple <?php echo $hidden ?>>
                    </td>
                </tr>
                <tr class="generic-tExclusion">
                    <td>Serviço(s):</td>
                    <td>Tempo de atendimento:</td>
                    <td>Valor total do atendimento:</td>
                </tr>
                <?php
                    $serviceAgent = getScheduledServices($schedule_ID);
                    if($serviceAgent !== false) {
                        foreach($serviceAgent as $service => $value) {
                            $service_ID = $value['id'];
                ?>  
                            <tr>
                                <td><?php echo getServiceDescription($service_ID) ?></td>
                                <td><?php echo getServiceTime($service_ID) ?></td>
                                <td>R$ <?php echo getServiceValue($service_ID) ?></td>
                            </tr>
                <?php
                        }
                    }
                ?>
                <tr>
                    <th colspan="3">DADOS DE PAGAMENTO</th>
                </tr>
                <tr>
                    <td class="generic-tExclusion" colspan="2">Tipo de Pagamento:</td>
                    <td colspan="2">
                        <select class="smallSelectInp" name="pay_type" <?php echo $disabled ?>>
                            <?php
                            $typePays = mysql_select_multi("SELECT * FROM `tipos_pagamento`;");
                            
                            if($typePays) {
                                foreach($typePays as $key => $value) { 
                                    if((int)$value['id'] === getPaymentType($pay_ID)) 
                                        echo '<option value="'.$value['id'].'" selected>'.$value['descricao'].'</option>';
                                    else
                                        echo '<option value="'.$value['id'].'">'.$value['descricao'].'</option>';
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <?php 
                    if(isSchedulingServicePerformed($schedule_ID)) { ?>
                        <tr>
                            <td class="generic-tExclusion" colspan="2">Data do Pagamento:</td>
                            <td colspan="2"><?php echo getPaymentDate($pay_ID).' '.getPaymentTime($pay_ID) ?></td>
                        </tr>
                <?php 
                    }
                ?>
                <tr>
                    <td class="generic-tExclusion" colspan="2">Valor</td>
                    <td colspan="2">R$ <input class="smallInputTxt" name="pay_value" value="<?php echo getPaymentValue($pay_ID) ?>" <?php echo $disabled ?>></input></td>
                </tr>
                <tr style="text-align: center;">
                    <?php
                        if(isSchedulingServicePerformed($schedule_ID) === false) {
                    ?>
                        <td class="generic-tExclusion" colspan="2">
                                    <?php setToken('tokenConfirmSchedule') ?>
                                    <input name="schedule_id" type="text" value="<?php echo $schedule_ID ?>" hidden/>
                                    <input name="token" type="text" value="<?php echo addToken('tokenConfirmSchedule') ?>" hidden/>
                                <button type="submit" class="enterButton">CONFIRMAR ATENDIMENTO</button>
                        </td>
                    <?php
                        }
                    ?>
            </form>
                    <?php
                        if(isSchedulingServicePerformed($schedule_ID) === false) {
                    ?>
                        <td class="generic-tExclusion" colspan="2">                
                            <form action="usersdata.php?subpage=schedulingEdit&data=<?php echo $_GET['data']; ?>&scheduling=<?php echo $schedule_ID; ?>&del=false" method="POST">
                                <?php setToken('tokenDeleteSchedule') ?>
                                <input name="schedule_id" type="text" value="<?php echo $schedule_ID ?>" hidden/>
                                <input name="token" type="text" value="<?php echo addToken('tokenDeleteSchedule') ?>" hidden/>
                                <button type="submit" class="backButton">EXCLUIR AGENDAMENTO</button>
                            </form>
                        </td>
                    <?php
                        }
                    ?>
                    <?php
                        if(isSchedulingServicePerformed($schedule_ID) === true && isAdmin(getAccountID($user_ID))) {
                    ?>
                        <td class="generic-tExclusion" colspan="2">           
                            <form action="" method="POST">
                                <?php setToken('tokenCancelSchedule') ?>
                                <input name="schedule_id" type="text" value="<?php echo $schedule_ID ?>" hidden/>
                                <input name="token" type="text" value="<?php echo addToken('tokenCancelSchedule') ?>" hidden/>
                                <button type="submit" class="backButton">REINICIAR ATENDIMENTO</button>
                            </form>
                        </td>
                    <?php
                        }
                    ?>
                </tr>
            </table>
        <br/>
        <a href='schedulingList.php'>
                    <button class='otherButton' type='button'>VOLTAR</button>
        </a>
<?php
    }
    
    include 'layout/overall/footer.php';
?>
