<?php 
require_once 'engine/init.php';
doProtect();
getPageAccess($user_data['id'], 2);
$titlepage = "Controle de Usuário";
include 'layout/overall/header.php';
$date = date("Y-m-d");


	/*********************************************************
	**********************************************************
	**
	**	PAGINA DE VALIDAÇÃO DE ABERTURA DE CAIXA
	**
	**
	**
	***********************************************************
	**********************************************************/

	// Faz as validações e verifica se tem erros
	if (empty($_POST) === false && getToken('cashdeskOpening')) {
        $required_fields = array('value');

        foreach($_POST as $key=>$value) {
            if (empty($value) && in_array($key, $required_fields) === true) {
                $errors[] = "É obrigatório o preenchimento de todos os campos com o (*).";
                break 1;
            }
        }

        if(!is_numeric($_POST['value']) || $_POST['value'] < 0) {
            $errors[] = 'O valor preenchido é invalido, favor preencher com um valor igual ou maior que zero.';
        }

        if(getCashierOpeningExist() !== false) {
            $errors[] = 'Já existe um caixa aberto, faça o fechamento dele antes de fazer uma nova abertura.';
        }
    }


    // Se as validações passaram sem erros entra aqui
	if (empty($_POST) === false && empty($errors) === true && getToken('cashdeskOpening')) {
        doCashierOpening($user_data['id'], $_POST['value']);
        echo output_msgs("O caixa foi aberto com sucesso!");
	}
	
	/*********************************************************
	**********************************************************
	**
	**	PAGINA DE VALIDAÇÃO DE FECHAMENTO DE CAIXA
	**
	**
	**
	***********************************************************
	**********************************************************/

	// Faz as validações e verifica se tem erros
	if (empty($_POST) === false && getToken('cashdeskClosing')) {
        $required_fields = array('value');

        foreach($_POST as $key=>$value) {
            if (empty($value) && in_array($key, $required_fields) === true) {
                $errors[] = "É obrigatório o preenchimento de todos os campos com o (*).";
                break 1;
            }
        }

        if(!is_numeric($_POST['value']) || $_POST['value'] < 0) {
            $errors[] = 'O valor preenchido é invalido, favor preencher com um valor igual ou maior que zero.';
        }

        if(getCashierOpeningExist() === false) {
            $errors[] = 'Não existe um caixa aberto, faça a abertura dele antes de fazer um fechamento.';
        }
    }


    // Se as validações passaram sem erros entra aqui
	if (empty($_POST) === false && empty($errors) === true && getToken('cashdeskClosing')) {
        doCashierClosing($user_data['id'], $_POST['value']);
        echo output_msgs("O caixa foi fechado com sucesso!");
	}





	// se as validações passaram com erros entra aqui
	if (empty($errors) === false) {
		echo output_errors($errors);
	}
	

?>

    <table class="generic-table">
    <?php
        if(getCashierOpeningExist() === false) {
    ?>
        <tr>
            <td class="generic-tExclusion" colspan="2">ABERTURA</td>
        </tr>
        <tr>
            <td>Data:</td>
            <td><input type="date" class="dateInput" disabled value="<?php echo $date; ?>"></td>
        </tr>
        <tr>
            <td>Hora:</td>
            <td><input type="time" class="dateInput" disabled value="<?php echo date("H:i"); ?>"></td>
        </tr>
        <form action="" method="POST">
            <tr>
                <td>Dinheiro(Troco Inicial):<font color="red">*</font></td>
                <td><input type="text" class="mediumInputTxt" name="value"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <?php setToken('cashdeskOpening') ?>
                    <input name="token" type="text" value="<?php echo addToken('cashdeskOpening') ?>" hidden/>
                    <input type="submit" class="enterButton" value="ABRIR"></input>
                </td>
            </tr>
        </form>
    <?php
        }
        elseif(getCashierOpeningExist() !== false) {
    ?>
        <tr>
            <td class="generic-tExclusion" colspan="2">FECHAMENTO</td>
        </tr>
        <tr>
            <td>Data:</td>
            <td><input type="date" class="dateInput" disabled value="<?php echo date("Y-m-d")?>"></td>
        </tr>
        <tr>
            <td>Hora:</td>
            <td><input type="time" class="dateInput" disabled value="<?php echo date("H:i")?>"></td>
        </tr>
        <tr>
            <td>Pix:</td>
            <td><input type="text" class="mediumInputTxt" value="<?php echo getPaymentsValueOfDay(getCashierOpeningID(), 4); ?>" disabled></td>
        </tr>
        <tr>
            <td>Crédito:</td>
            <td><input type="text" class="mediumInputTxt" value="<?php echo getPaymentsValueOfDay(getCashierOpeningID(), 3); ?>" disabled></td>
        </tr>
        <tr>
            <td>Débito:</td>
            <td><input type="text" class="mediumInputTxt" value="<?php echo getPaymentsValueOfDay(getCashierOpeningID(), 2); ?>" disabled></td>
        </tr>
        <form action="" method="POST">
            <tr>
                <td>Dinheiro:</td>
                <td><input type="text" class="mediumInputTxt" name="value"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <?php setToken('cashdeskClosing') ?>
                    <input name="token" type="text" value="<?php echo addToken('cashdeskClosing') ?>" hidden/>
                    <input type="submit" class="otherButton" value="FECHAR"></input>
                </td>
            </tr>
        </form>
    <?php
        }
    ?>
    </table>

<?php

include 'layout/overall/footer.php';
?>