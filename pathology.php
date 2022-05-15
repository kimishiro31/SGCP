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
        if (empty($_POST) === false && getToken('tokenPathology')) {
            $required_fields = array('gravity', 'pathology');
             
            foreach($_POST as $key => $value) {
                if ($key !== 'token') {
                    if (empty($value) && in_array($key, $required_fields) === true) {
                        $errors[] = "Obrigatório o preenchimento de todos os campos.";
                        break 1;
                    }
                }
           }
           
           if ($_POST['gravity'] <= 0 || $_POST['gravity'] >= 4) {
                $errors[] = "Necessário preencher com a gravidade.";
            }
        }

        /*********************************************************
        **
        ** SE O FORMULARIO DE AGENDAMENTO VALIDAR SEM ERROS
        **
        **********************************************************/
        if (empty($_POST) === false && getToken('tokenPathology') && empty($errors)) {

            doAddPathology($_POST['pathology'], $_POST['gravity']);
            echo output_msgs("Patologia adicionada com sucesso.");
            header('refresh: 2, pathology.php');
        }
        
        
        /*********************************************************
        **
        ** VALIDAÇÃO DO FORMULARIO PARA DELETAR PATOLOGIA
        **
        **********************************************************/
        if (empty($_POST) === false && getToken('tokenPathologyDelete')) {
           if (isPathologyExist($_POST['id_pathology']) === false) {
                $errors[] = "Essa patologia não existe.";
            }
        }

        /*********************************************************
        **
        ** SE O FORMULARIO PARA DELETAR PATOLOGIA VALIDAR SEM ERROS
        **
        **********************************************************/
        if (empty($_POST) === false && getToken('tokenPathologyDelete') && empty($errors)) {
            doRemovePathology($_POST['id_pathology']);
            echo output_msgs("Patologia removida com sucesso.");
            header('refresh: 2, pathology.php');
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
<div id="filterContent">
    <form action="" method="post">
        <input type="text" name="pathology" class="smallInputTxt" placeholder="PATOLOGIA"></input>
        <select class="smallSelectInp" name="gravity">
            <option value='0'>-- GRAVIDADE --</option>
            <option value='1'>BAIXA</option>
            <option value='2'>MEDIA</option>
            <option value='3'>ALTA</option>
        </select>
        <?php setToken('tokenPathology') ?>
        <input name="token" type="text" value="<?php echo addToken('tokenPathology') ?>" hidden/>
        <input type="submit" class="enterButton" value="ADICIONAR"></input>
    </form>
</div>
<br/>
<br/>
    <div id="pth-content">
        <table class="pth-table">
            <tr>
                <th>PATOLOGIAS</th>
                <th>GRAVIDADE</th>
                <th>OPÇÕES</th>
            </tr>
            <?php
                $query = mysql_select_multi("SELECT * FROM `patologias`;");        
                if($query) {
                    setToken('tokenPathologyDelete');
                    foreach($query as $key => $value) {
            ?>
                    <tr>
                        <td><?php echo $value['nome'] ?></td>
                        <td><?php echo doConvertPriorityToString($value['type']); ?></td>
                        <td>
                            <form action="" method="post">
                                <input name="id_pathology" value="<?php echo $value['id']; ?>" hidden>
                                <input name="token" type="text" value="<?php echo addToken('tokenPathologyDelete') ?>" hidden/>
                                <button type="submit" class="buttonFilter">
                                    <i class="fa fa-times-circle iClose" aria-hidden="true"></i>
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