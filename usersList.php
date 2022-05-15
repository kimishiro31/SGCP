<?php 
require_once 'engine/init.php';
doProtect();
getPageAccess($user_data['id'], 2);
$titlepage = "Controle de Usuário";
include 'layout/overall/header.php'; 
?>
<div id="filterContent">
    <form action="" method="GET">
        <select class="smallSelectInp" name="search">
            <option value='nome'>NOME</option>
            <option value='cpf'>CPF</option>
            <option value='cep'>CEP</option>
        </select>
        <input type="text" name="data" class="mediumInputTxt" placeholder="NOME / CPF / CEP / RG"></input>
        <input type="submit" class="otherButton" value="PROCURAR"></input>
    </form>
</div>
<br/>
<br/>
<!-- DESIGN DA TELA DE BUSCA DE USUARIOS -->
<center>
    <?php
    if(!isset($_GET['order'])) $_GET['order'] = 'desc';

    $apage = isset($_GET['page']) ? $_GET['page'] : 1;
    $rowsPerPage = $config['rowsPerPage'];
    $ppage = ($apage * $rowsPerPage) - $rowsPerPage;

    $result = mysql_select_multi("SELECT * FROM usuarios");
    if($result) {
        $total = count($result);
        $countLink = ceil($total / $rowsPerPage);
        for ($i = $apage - 3; $i <= $countLink; ++$i) {
            if($i < 1) $i = 1;
            
            $type = (isset($_GET['type'])) ? '&type='.$_GET['type'] : '';
            $order = (isset($_GET['order'])) ? '&order='.$_GET['order'] : '';
            $search = (isset($_GET['search'])) ? '&search='.$_GET['search'] : '';
            $data = (isset($_GET['data'])) ? '&data='.$_GET['data'] : '';
            if($i == $apage)
                echo ' ['. $i . '] ';
            else
                echo '<a href="?page='.$i.$type.$order.$search.$data.'">[ '.$i.' ]</a> ';
        }
    }
    ?>
</center><br/>
<div id="us-content">
    <table class="us-table">
        <tr>
            <th>FOTO</th>
            <th onclick="location.href='usersList.php?page=<?php echo $apage; ?>&order=<?php echo getOrder($_GET['order']); ?>'">NOME</th>
            <th onclick="location.href='usersList.php?page=<?php echo $apage; ?>&type=1&order=<?php echo getOrder($_GET['order']); ?>'">DATA DE NASCIMENTO</th>
            <th onclick="location.href='usersList.php?page=<?php echo $apage; ?>&type=2&order=<?php echo getOrder($_GET['order']); ?>'">CPF</th>
            <th onclick="location.href='usersList.php?page=<?php echo $apage; ?>&type=3&order=<?php echo getOrder($_GET['order']); ?>'">GENERO</th>
            <th>TELEFONE</th>
        </tr>
        <?php
            if ((isset($_GET['search']) && !empty($_GET['search'])) && (isset($_GET['data']) && !empty($_GET['data']))) {
                $search = strtoupper($_GET['search']);
                $data = $_GET['data'];
                if(strtoupper($search) === 'NOME'):
                    if(count(explode(' ', $data)) <= 1)
                        $query = mysql_select_multi("SELECT `id` FROM `usuarios` where `primeiro_nome` LIKE '".strtoupper(doSliceName($data))."%' ORDER by `primeiro_nome` ".getOrder($_GET['order'])." LIMIT $ppage, $rowsPerPage;");
                    else
                        $query = mysql_select_multi("SELECT `id` FROM `usuarios` where `primeiro_nome` LIKE '".strtoupper(doSliceName($data))."%' and `ultimo_nome` LIKE '".strtoupper(doSliceName($data, true))."%' ORDER by `primeiro_nome` ".getOrder($_GET['order'])." LIMIT $ppage, $rowsPerPage;;");
                elseif($search === 'CPF'):
                    $query = mysql_select_multi("SELECT `id` FROM `usuarios` where `cpf`='$data' ORDER by `cpf` ".getOrder($_GET['order'])." LIMIT $ppage, $rowsPerPage;;");                    
                elseif($search === 'CEP'):
                    $query = mysql_select_multi("SELECT `id` FROM `usuarios` where `cep`='$data' ORDER by `cep` ".getOrder($_GET['order'])." LIMIT $ppage, $rowsPerPage;;");                    
                endif;
            }
            else {    
                if(isset($_GET['type']) && $_GET['type'] == 1)
                    $query = mysql_select_multi("SELECT `id` FROM `usuarios` ORDER by `data_nascimento` ".getOrder($_GET['order'])." LIMIT $ppage, $rowsPerPage;");
                elseif(isset($_GET['type']) && $_GET['type'] == 2)
                    $query = mysql_select_multi("SELECT `id` FROM `usuarios` ORDER by `cpf` ".getOrder($_GET['order'])." LIMIT $ppage, $rowsPerPage;");
                elseif(isset($_GET['type']) && $_GET['type'] == 3)
                    $query = mysql_select_multi("SELECT `id` FROM `usuarios` ORDER by `genero` ".getOrder($_GET['order'])." LIMIT $ppage, $rowsPerPage;");
                else
                    $query = mysql_select_multi("SELECT `id` FROM `usuarios` ORDER by `primeiro_nome` ".getOrder($_GET['order'])." LIMIT $ppage, $rowsPerPage;");
            }
            
            if($query) { 
                foreach($query as $column) {
                    $user_id = $column['id'];
                    $account_id = getAccountID($user_id);
        ?>
        <tr onclick="location.href='userViewer.php?data=<?php echo $column['id'] ?>'">
            <th>
                <div class="us-imgFrame">
                    <a href="">
                        <img src="<?php echo getUserFolderForIMG(getUserCPF($user_id)).'/'.getUserPhotoName($user_id); ?>"></img>
                    </a>
                </div>
            </th>
            <th><?php echo getUserCompleteName($user_id); ?></th>
            <th><?php echo getUserBirthDate($user_id) ?></th>
            <th><?php echo doFormatCPF(getUserCPF($user_id)) ?></th>
            <th><?php echo getUserGender($user_id) ?></th>
            <th><?php echo getAccountPPhone($account_id) ?></th>
        </tr>
        <?php
               }
            }
        ?>
    </table>
</div>
<br/><br/>


<?php
include 'layout/overall/footer.php';
?>