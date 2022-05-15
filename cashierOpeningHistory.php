<?php 
require_once 'engine/init.php';
doProtect();
getPageAccess($user_data['id'], 2);

if($subpage == '') {
    $titlepage = "Controle de Usuário";
    include 'layout/overall/header.php';
?>

<center>
<div id="filterContent">
    <form action="" method="GET">
        <input type="date" name="date" class="dateInput"></input>
        <input type="submit" class="otherButton" value="PROCURAR"></input>
    </form>
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
    ?>
    </center>
    <br/><br/>
        <div id="cdh-content">
            <table class="cdh-table">
                <tr>
                    <th>Nº</th>
                    <th>DATA ABERTURA</th>
                    <th>DATA FECHAMENTO</th>
                    <th>STATUS</th>
                    <th>OPÇÕES</th>
                </tr>
            <?php 


                if(isset($_GET['type']) && $_GET['type'] == 1)
                    $query = mysql_select_multi("SELECT `id` FROM `caixa` ORDER by `data_abertura` ".getOrder($_GET['order'])." LIMIT $ppage, $rowsPerPage;");
                elseif(isset($_GET['type']) && $_GET['type'] == 2)
                    $query = mysql_select_multi("SELECT `id` FROM `caixa` ORDER by `data_fechamento` ".getOrder($_GET['order'])." LIMIT $ppage, $rowsPerPage;");
                elseif(isset($_GET['type']) && $_GET['type'] == 3)
                    $query = mysql_select_multi("SELECT `id` FROM `caixa` ORDER by `status` ".getOrder($_GET['order'])." LIMIT $ppage, $rowsPerPage;");
                elseif(isset($_GET['date']) && (!empty($_GET['date'])))
                    $query = mysql_select_multi("SELECT `id` FROM `caixa` where `data_abertura`='".$_GET['date']."' ORDER by id ".getOrder($_GET['order'])." LIMIT $ppage, $rowsPerPage;");
                else
                    $query = mysql_select_multi("SELECT `id` FROM `caixa` ORDER by id ".getOrder($_GET['order'])." LIMIT $ppage, $rowsPerPage;");
                
                if($query) {
                    foreach($query as $key => $value) {
                        $cashier_ID = $value['id'];
                ?>
                    <tr <?php if(getCashierStatus($cashier_ID) == 1) {?> style="background-color: rgb(85 192 221);" <?php } ?> onclick="location.href='cashierOpeningHistory.php?subpage=check&desk=<?php echo $value['id']; ?>'">
                        <td><?php echo $cashier_ID; ?></td>
                        <td><?php echo getCashierOpeningDate($cashier_ID).' - '.getCashierOpeningHour($cashier_ID) ?></td>
                        <td><?php echo getCashierClosingDate($cashier_ID).' - '.getCashierClosingHour($cashier_ID) ?></td>
                        <td><?php echo doCashierConvertStatusInString(getCashierStatus($cashier_ID)); ?></td>
                        <td></td>
                    </tr>
                <?php
                    }
                }
            ?>
            
            </table>
        </div>
<?php
include 'layout/overall/footer.php';
}

if($subpage == 'check') {
    $titlepage = "Controle de Usuário";
    include 'layout/overall/header.php';
    $cashier_ID = $_GET['desk'];
?>
    <table class="generic-table">
        <tr>
            <td class="generic-tExclusion" colspan="2">ABERTURA</td>
        </tr>
        <tr>
            <td>Data:</td>
            <td><?php echo getCashierOpeningDate($cashier_ID); ?></td>
        </tr>
        <tr>
            <td>Hora:</td>
            <td><?php echo getCashierOpeningHour($cashier_ID); ?></td>
        </tr>
        <tr>
            <td>Dinheiro(Troco Inicial):</td>
            <td><?php echo getCashierOpeningValue($cashier_ID); ?></td>
        </tr>
        <tr>
            <td>Colaborador:</td>
            <td><?php echo getUserCompleteName(getCashierOpeningCollaborator($cashier_ID)); ?></td>
        </tr>
        <tr>
            <td class="generic-tExclusion" colspan="2">FECHAMENTO</td>
        </tr>
        <tr>
            <td>Data:</td>
            <td><?php echo getCashierClosingDate($cashier_ID); ?></td>
        </tr>
        <tr>
            <td>Hora:</td>
            <td><?php echo getCashierClosingHour($cashier_ID); ?></td>
        </tr>
        <tr>
            <td>Pix:</td>
            <td><?php echo getPaymentsValueOfDay(getCashierOpeningID(), 4); ?></td>
        </tr>
        <tr>
            <td>Crédito:</td>
            <td><?php echo getPaymentsValueOfDay(getCashierOpeningID(), 3); ?></td>
        </tr>
        <tr>
            <td>Débito:</td>
            <td><?php echo getPaymentsValueOfDay(getCashierOpeningID(), 2); ?></td>
        </tr>
        <tr>
            <td>Dinheiro:</td>
            <td><?php echo getCashierClosingValue($cashier_ID); ?></td>
        </tr>
        <tr>
            <td>Colaborador:</td>
            <td><?php echo getUserCompleteName(getCashierClosingCollaborator($cashier_ID)); ?></td>
        </tr>
        <tr>
            <td class="generic-tExclusion" colspan="2">DETALHES</td>
        </tr>
        <tr>
            <td>Saldo Inicial:</td>
            <td><?php echo getCashierOpeningValue($cashier_ID); ?></td>
        </tr>
        <tr>
            <td>Saldo Final:</td>
            <td><?php echo getCashierClosingValue($cashier_ID); ?></td>
        </tr>
    </table>

<?php
include 'layout/overall/footer.php';
}


?>

?>