<?php 
require_once 'engine/init.php';
doProtect();
getPageAccess($user_data['id'], 3);
$titlepage = "Controle de Usuário";
include 'layout/overall/header.php';


        /*********************************************************
        **
        ** VALIDAÇÃO DO FORMULARIO DE AGENDAMENTO
        **
        **********************************************************/
        if (empty($_POST) === false && getToken('tokenServiceAdd')) {
            $required_fields = array('description', 'value', 'time', 'priority');
             
            foreach($_POST as $key => $value) {
                if ($key !== 'token') {
                    if (empty($value) && in_array($key, $required_fields) === true) {
                        $errors[] = "Obrigatório o preenchimento de todos os campos.";
                        break 1;
                    }
                }
           }
           
           if (!is_numeric($_POST['value'])) {
               $errors[] = "É obrigatório o valor ser numero.";
           }
           elseif($_POST['value'] < 0) {
               $errors[] = "O valor tem que ser positivo.";
           }

           if ($_POST['priority'] <= 0 || $_POST['priority'] >= 4) {
                $errors[] = "Necessário preencher com a gravidade.";
            }
        }

        /*********************************************************
        **
        ** SE O FORMULARIO DE AGENDAMENTO VALIDAR SEM ERROS
        **
        **********************************************************/
        if (empty($_POST) === false && getToken('tokenServiceAdd') && empty($errors)) {
            doAddService($_POST['description'], $_POST['priority'], $_POST['value'], $_POST['time']);
            echo output_msgs("Serviço adicionado com sucesso.");
            header('refresh: 2, services.php');
        }
        
        
        /*********************************************************
        **
        ** VALIDAÇÃO DO FORMULARIO PARA DELETAR PATOLOGIA
        **
        **********************************************************/
        if (empty($_POST) === false && getToken('tokenServiceDelete')) {
           if (isServiceExist($_POST['id_service']) === false) {
                $errors[] = "Esse serviço não existe.";
            }
        }

        /*********************************************************
        **
        ** SE O FORMULARIO PARA DELETAR PATOLOGIA VALIDAR SEM ERROS
        **
        **********************************************************/
        if (empty($_POST) === false && getToken('tokenServiceDelete') && empty($errors)) {
            doRemoveService($_POST['id_service']);
            echo output_msgs("Serviço removido com sucesso.");
            header('refresh: 2, services.php');
        }
    
        

        /*********************************************************
        **
        ** VALIDAÇÃO DO FORMULARIO PARA ALTERAR SERVICOS
        **
        **********************************************************/
        if (empty($_POST) === false && getToken('tokenServiceUpdate')) {
            $required_fields = array('description', 'value', 'time', 'priority');
             
            foreach($_POST as $key => $value) {
                if ($key !== 'token') {
                    if (empty($value) && in_array($key, $required_fields) === true) {
                        $errors[] = "Obrigatório o preenchimento de todos os campos.";
                        break 1;
                    }
                }
           }
           
            if (isServiceExist($_POST['id_service']) === false) {
                $errors[] = "Esse serviço não existe.";
            }
           
           if (!is_numeric($_POST['value'])) {
               $errors[] = "É obrigatório o valor ser numero.";
           }
           elseif($_POST['value'] < 0) {
               $errors[] = "O valor tem que ser positivo.";
           }

           if ($_POST['priority'] <= 0 || $_POST['priority'] >= 4) {
                $errors[] = "Necessário preencher com a gravidade.";
            }
         }
 
         /*********************************************************
         **
         ** SE O FORMULARIO ALTERAR SERVICOS VALIDAR SEM ERROS
         **
         **********************************************************/
         if (empty($_POST) === false && getToken('tokenServiceUpdate') && empty($errors)) {
             doUpdateService($_POST['id_service'], $_POST['description'], $_POST['priority'], $_POST['value'], $_POST['time']);
             echo output_msgs("Serviço alterado com sucesso.");
             header('refresh: 2, services.php');
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
<?php
if(getToken('tokenServiceEdit')) {
?>
    <div id="filterContent" style="width: 800px">
        <form action="" method="post">
            <input name="id_service" value="<?php echo $_POST['id_service']; ?>" hidden>
            <input type="text" name="description" class="smallInputTxt" placeholder="Descrição" value="<?php if(getServiceDescription($_POST['id_service']) !== false) echo getServiceDescription($_POST['id_service']); ?>"></input>
            <input type="text" name="value" class="smallInputTxt" placeholder="Valor"  value="<?php if(getServiceValue($_POST['id_service']) !== false) echo getServiceValue($_POST['id_service']); ?>"></input>
            <input type="time" name="time" class="smallInputTxt" value="<?php if(getServiceTime($_POST['id_service']) !== false) echo getServiceTime($_POST['id_service']); ?>"></input>
            <select class="smallSelectInp" name="priority">
                <option value='0'>-- PRIORIDADE --</option>
                <option value='1' <?php if(getServicePriority($_POST['id_service']) == 1) echo 'selected'; ?>>BAIXA</option>
                <option value='2' <?php if(getServicePriority($_POST['id_service']) == 2) echo 'selected'; ?>>MEDIA</option>
                <option value='3' <?php if(getServicePriority($_POST['id_service']) == 3) echo 'selected'; ?>>ALTA</option>
            </select>
            <?php setToken('tokenServiceUpdate') ?>
            <input name="token" type="text" value="<?php echo addToken('tokenServiceUpdate') ?>" hidden/>
            <input type="submit" class="enterButton" value="ALTERAR"></input>
        </form>
    </div>
<?php
}
else {
?>
    <div id="filterContent" style="width: 800px">
        <form action="" method="post">
            <input type="text" name="description" class="smallInputTxt" placeholder="Descrição"></input>
            <input type="text" name="value" class="smallInputTxt" placeholder="Valor"></input>
            <input type="time" name="time" class="smallInputTxt"></input>
            <select class="smallSelectInp" name="priority">
                <option value='0'>-- PRIORIDADE --</option>
                <option value='1'>BAIXA</option>
                <option value='2'>MEDIA</option>
                <option value='3'>ALTA</option>
            </select>
            <?php setToken('tokenServiceAdd') ?>
            <input name="token" type="text" value="<?php echo addToken('tokenServiceAdd') ?>" hidden/>
            <input type="submit" class="enterButton" value="ADICIONAR"></input>
        </form>
    </div>
<?php
}
?>
<br/>
<br/>
    <div id="pth-content">
        <table class="pth-table">
            <tr>
                <th>DESCRIÇÃO</th>
                <th>VALOR</th>
                <th>TEMPO</th>
                <th>PRIORIDADE</th>
                <th>OPÇÕES</th>
            </tr>
            <?php
                $query = doListServices();
                if($query !== false) {
                    setToken('tokenServiceDelete');
                    setToken('tokenServiceEdit');
                    foreach($query as $key => $value) {
            ?>
                    <tr>
                        <td><?php echo $value['descricao'] ?></td>
                        <td><?php echo $value['valor'] ?></td>
                        <td><?php echo $value['tempo'] ?></td>
                        <td><?php echo doConvertPriorityToString($value['prioridade']) ?></td>
                        <td style="display: inline-flex;">
                            <form action="" method="post">
                                <input name="id_service" value="<?php echo $value['id']; ?>" hidden>
                                <input name="token" type="text" value="<?php echo addToken('tokenServiceDelete') ?>" hidden/>
                                <button type="submit" class="buttonFilter">
                                    <i class="fa fa-times-circle iClose" aria-hidden="true"></i>
                                </button>
                            </form>
                            <form action="" method="post">
                                <input name="id_service" value="<?php echo $value['id']; ?>" hidden>
                                <input name="token" type="text" value="<?php echo addToken('tokenServiceEdit') ?>" hidden/>
                                <button type="submit" class="buttonFilter">
                                    <i class="fa fa-pencil-square iEdit" aria-hidden="true"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
            <?php
                    }
                }
            ?>
        </table>
    </div>
</center>
<?php
include 'layout/overall/footer.php';
?>