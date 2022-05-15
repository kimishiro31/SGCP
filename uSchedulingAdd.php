<?php 
require_once 'engine/init.php';
doProtect();
getPageAccess($user_data['id'], 2);
    $titlepage = "Controle de Usuário";
    include 'layout/overall/header.php';

        /*********************************************************
        **
        ** VALIDAÇÃO DO FORMULARIO DE AGENDAMENTO
        **
        **********************************************************/
        if (empty($_POST) === false && getToken('tokenScheduling')) {
            $required_fields = array('service1', 'collaborator', 'dateSchedule', 'hours');
            $service_fields = array('service2', 'service3', 'service4');

            $dateSch = $_POST['dateSchedule'];
             
            foreach($_POST as $key => $value) {
                if ($key !== 'token') {
                    if (empty($value) && in_array($key, $required_fields) === true) {
                        $errors[] = "Obrigatório o preenchimento de todos os campos.";
                        break 1;
                    }                    
                    elseif($key == 'service2' && $value != 0 && isServiceExist($_POST['service2']) === false) {
                        $errors[] = "Houve algum erro, o serviço mencionado não existe.";
                        break 1;
                    }
                    elseif($key == 'service3' && $value != 0 && isServiceExist($_POST['service3']) === false) {
                        $errors[] = "Houve algum erro, o serviço mencionado não existe.";
                        break 1;
                    }
                    elseif($key == 'service4' && $value != 0 && isServiceExist($_POST['service4']) === false) {
                        $errors[] = "Houve algum erro, o serviço mencionado não existe.";
                        break 1;
                    }
                }
           }

           
            if(isServiceExist($_POST['service1']) === false) {
               $errors[] = "Houve algum erro, o serviço mencionado não existe.";
            }
            
            if(isUserExist($_POST['collaborator']) === false) {
                $errors[] = "O colaborator mencionado não existe.";
            }
            elseif(getGroup($_POST['collaborator']) <= 1) {
               $errors[] = "Esse usuário não pode ser indicado como podologo.";
            }

            if((int)date("d", strtotime($_POST['dateSchedule'])) < 1 || (int)date("d", strtotime($_POST['dateSchedule'])) > getDaysInMonth((int)date("m", strtotime($_POST['dateSchedule'])), (int)date("Y", strtotime($_POST['dateSchedule'])))) {
                $errors[] = "O dia escolhido é inexistente no calêndario.";
            }
            elseif(date("Y-m-d H:i") > $dateSch.' '.$_POST['hours']) {
                $errors[] = "A data escolhida é menor do que a data atual.";
            }
            elseif((int)date("m", strtotime($_POST['dateSchedule'])) < 1 || (int)date("m", strtotime($_POST['dateSchedule'])) > 12) {
                $errors[] = "Mês escolhido não existe no calendario.";
            }

            if(isTimeValidation($_POST['hours']) === false) {
                $errors[] = "Horário invalido, favor escolher outro.";
            }
            elseif(getFreeScheduling($dateSch, $_POST['hours']) !== false) {
                $errors[] = "A data escolhida não está livre, favor escolher outra data/horário.";
            }
        }

        /*********************************************************
        **
        ** SE O FORMULARIO DE AGENDAMENTO VALIDAR SEM ERROS
        **
        **********************************************************/
        if (empty($_POST) === false && getToken('tokenScheduling') && empty($errors)) {
			$user_id = $_GET['data'];

            $register_data = array(
                'USUARIO_ID' => $user_id,
                'AGENDADOR_ID' => $user_data['id'],
                'DATA_AGENDAMENTO' => time(),
                'DATA_AGENDADA' => $_POST['dateSchedule'],
                'HORA_AGENDADA' => $_POST['hours'],
                'COLLABORATOR' => $_POST['collaborator'],
                'SERVICE1' => $_POST['service1'],
                'SERVICE2' => $_POST['service2'],
                'SERVICE3' => $_POST['service3'],
                'SERVICE4' => $_POST['service4']
            );

            $msg[] = "Agendamento efetuado com sucesso!!";
            $msg[] = "Foi encaminhado uma mensagem para o telefone cadastrado, informando a data e horário do agendamento.";
            $msg = implode('<br/>', $msg);
            doCreateScheduling($register_data);
            header('refresh: 2, userViewer.php?data='.$_GET['data']);
            echo output_msgs($msg);
        }
        
        /*********************************************************
        **
        ** SE O FORMULARIO DE AGENDAMENTO VALIDAR COM ERROS
        **
        **********************************************************/
        if (empty($errors) === false) {
            header("HTTP/1.1 401 Not Found");
            echo output_errors($errors);
        }
?>
<center>
    <form action="uSchedulingAdd.php?data=<?php echo $_GET['data'] ?>" method="POST">
        Escolha o tipo de serviço:<br/>
        <select class='bigSelectInp' name="service1">
            <?php 
                foreach(doListServices(1) as $key => $value) {
                    echo '<option value="'.$value['id'].'">'.$value['descricao'].' | R$ '.$value['valor'].'</option>';
                }
            ?>
            <option value='0' selected>-- SERVICO 1 --</option>
        </select>
        <select class='bigSelectInp' name="service2">
            <?php 
                foreach(dolistServices(1) as $key => $value) {
                    echo '<option value="'.$value['id'].'">'.$value['descricao'].' | R$ '.$value['valor'].'</option>';
                }
            ?>
            <option value='0' selected>-- SERVICO 2 --</option>
        </select>
        <select class='bigSelectInp' name="service3">
            <?php 
                foreach(doListServices(1) as $key => $value) {
                    echo '<option value="'.$value['id'].'">'.$value['descricao'].' | R$ '.$value['valor'].'</option>';
                }
            ?>
            <option value='0' selected>-- SERVICO 3 --</option>
        </select>
        <select class='bigSelectInp' name="service4">
            <?php 
                foreach(doListServices(1) as $key => $value) {
                    echo '<option value="'.$value['id'].'">'.$value['descricao'].' | R$ '.$value['valor'].'</option>';
                }
            ?>
            <option value='0' selected>-- SERVICO 4 --</option>
        </select>
        <br/><br/>

        Escolha quem vai atender:<br/>
        <select class='bigSelectInp' name="collaborator">
            <?php 
                foreach(doListCollaborator(1) as $key => $value) {
                    echo '<option value="'.$value['id'].'">'.$value['primeiro_nome'].' '.$value['ultimo_nome'].'</option>';
                }
            ?>
            <option value='0' selected>-- PROFISSIONAL --</option>
        </select>
        <br/><br/>

        Escolha a data e o horário:<br/>
        <input type="date" name="dateSchedule" id="dateSchedule" class="dateInput">

        <select class='bigSelectInp' id="hours" name="hours">
            <option value="0">-- HORÁRIOS --</option>
        </select>
        <br/><br/>

        <?php setToken('tokenScheduling') ?>
        <input name="token" id="token" type="text" value="<?php echo addToken('tokenScheduling') ?>" hidden/>
        <button type="submit" class="enterButton">CONFIRMAR AGENDAMENTO</button>

        <a href='userviewer.php?data=<?php echo $_GET['data']; ?>&scheduleAlter'>
            <button class='otherButton' type='button'>VOLTAR</button>
        </a>
    </form>
    
    <!-- JAVA SCRIPT -->
    <script type="text/javascript">
            $(document).ready(function(){                
				$("#dateSchedule").change(function(){
						var dateSchedule = $(this).val();
						var token = $('#token').val();
						$.post("ajaxRequisit.php?subpage=hours", {token:token, dateSchedule: dateSchedule}, 
						function(result) {
								$("#hours").empty();
								if (result) {
									var options = '';
									options = options + '<option value="0">-- HORÁRIO --</option>'
									$.each(result, 
										function(i,v) {
												options = options + '<option value="'+v+'">'+ v +'</option>'
										});

									$("#hours").html(options);
								}
							}, "json");
				});
			})
</script>
</center>

<?php
    
    include 'layout/overall/footer.php';
?>