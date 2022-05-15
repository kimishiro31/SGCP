<?php
    require_once 'engine/init.php';
    doProtect();
    getPageAccess($user_data['id'], 2);
    $titlepage = "Controle de Usuário";
    include 'layout/overall/header.php';

?>

    <div id="bt-Schedulling"> 
        <center>
            <form action="" method="GET">
                <input type="date" name="date" class="dateInput"></input>
                <input type="submit" class="otherButton" value="PROCURAR"></input>
                
                <a href="schedulingAdd.php">
                    <button type="button" class="enterButton">novo agendamento</button>
                </a>
            </form>
        </center>
    </div>
    <br/><br/>
<?php
    $apage = isset($_GET['page']) ? $_GET['page'] : 1;
    $rowsPerPage = $config['rowsPerPage'];
    $ppage = ($apage * $rowsPerPage) - $rowsPerPage;

    if(!isset($_GET['order'])) $_GET['order'] = 'desc';

    $result = mysql_select_multi("SELECT * FROM `agendamentos`");
    
    if($result !== false) {
        $total = count($result);
        $countLink = ceil($total / $rowsPerPage);
        echo '<center>';
        for ($i = $apage - 3; $i <= $countLink; ++$i) {
            
            if($i < 1) $i = 1;
            
            $type = (isset($_GET['type'])) ? '&type='.$_GET['type'] : '';
            $order = (isset($_GET['order'])) ? '&order='.$_GET['order'] : '';

            if($i == $apage)
                echo ' ['. $i . '] ';
            else
                echo '<a href="?page='.$i.$type.$order.'">[ '.$i.' ]</a> ';
        }
    }
?><br/><br/>

    
    <div id="sc-content">
        <table class="sc-table">
            <tr>
                <th onclick="location.href='schedulingList.php?page=<?php echo $apage; ?>&order=<?php echo getOrder($_GET['order']); ?>'">Nº</th>
                <th onclick="location.href='schedulingList.php?page=<?php echo $apage; ?>&type=1&order=<?php echo getOrder($_GET['order']); ?>'">Data do Agendamento</th>
                <th onclick="location.href='schedulingList.php?page=<?php echo $apage; ?>&type=2&order=<?php echo getOrder($_GET['order']); ?>'">Data do Atendimento</th>
                <th onclick="location.href='schedulingList.php?page=<?php echo $apage; ?>&type=3&order=<?php echo getOrder($_GET['order']); ?>'">STATUS</th>
            </tr>

        <?php 


            if(isset($_GET['type']) && $_GET['type'] == 1)
                $query = mysql_select_multi("SELECT `id`, `usuario_id`, `data_agendamento`, `data_agendada`, `hora_agendada`, `status` FROM `agendamentos` ORDER by `data_agendamento` ".getOrder($_GET['order'])." LIMIT $ppage, $rowsPerPage;");
            elseif(isset($_GET['type']) && $_GET['type'] == 2)
                $query = mysql_select_multi("SELECT `id`, `usuario_id`, `data_agendamento`, `data_agendada`, `hora_agendada`, `status` FROM `agendamentos` ORDER by `data_agendada` ".getOrder($_GET['order'])." LIMIT $ppage, $rowsPerPage;");
            elseif(isset($_GET['type']) && $_GET['type'] == 3)
                $query = mysql_select_multi("SELECT `id`, `usuario_id`, `data_agendamento`, `data_agendada`, `hora_agendada`, `status` FROM `agendamentos` ORDER by `status` ".getOrder($_GET['order'])." LIMIT $ppage, $rowsPerPage;");
            elseif(isset($_GET['date']) && (!empty($_GET['date'])))
                $query = mysql_select_multi("SELECT * FROM `agendamentos` where `data_agendada`='".$_GET['date']."' ORDER by id ".getOrder($_GET['order'])." LIMIT $ppage, $rowsPerPage;");
            else
                $query = mysql_select_multi("SELECT `id`, `usuario_id`, `data_agendamento`, `data_agendada`, `hora_agendada`, `status` FROM `agendamentos` ORDER by id ".getOrder($_GET['order'])." LIMIT $ppage, $rowsPerPage;");
            
            if($query) {
                foreach($query as $key => $value) {
            ?>
                <tr <?php if(isSchedulingServicePerformed($value['id'])) {?> style="background-color: rgb(85 192 221);" <?php } ?> onclick="location.href='schedulingEdit.php?data=<?php echo $value['usuario_id'] ?>&scheduling=<?php echo $value['id'] ?>'">
                    <td><?php echo $value['id'] ?></td>
                    <td><?php echo date("d/m/Y - H:i", $value['data_agendamento']) ?></td>
                    <td><?php echo date('d/m/Y', strtotime($value['data_agendada'])).' - '.$value['hora_agendada'].' as '.doTimeConvert($value['hora_agendada'], getTimeTotalService($value['id'], true), true) ?></td>
                    <td><?php if(isSchedulingServicePerformed($value['id']) !== false) echo 'ATENDIMENTO EFETUADO'; else echo 'AGUARDANDO ATENDIMENTO'; ?></td>
                </tr>
            <?php
                }
            }
        ?>
        
        </table>
    </div>

<?php

    include 'layout/overall/footer.php';
?>
