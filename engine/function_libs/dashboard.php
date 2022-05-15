<?php


/*
getDashBoardGraphicPatients();
Descrição: Retorna com o calculo da diferença entre o mês anterior e atual de novos pacientes
*/
function getDashBoardGraphicPatients() {
    $count = 0;
    
    /* INFORMAÇÕES DO MÊS ATUAL */
    $dateActual = array(
        'start' => date("Y-m-01"),
        'end' => date("Y-m-t")
    );

    $countActual = mysql_select_single("SELECT COUNT(*) as `total_usuarios` FROM `usuarios` INNER JOIN `contas` ON `usuarios`.`conta_id` = `contas`.`id` where `contas`.`criacao`>='".$dateActual['start']."' and `contas`.`criacao`<='".$dateActual['end']."' and `contas`.`nv_acesso`=0 ;");

    /* INFORMAÇÕES DO MÊS PASSADO */
    $dateLast = array(
        'start' => date("Y-m-01", strtotime('last month')),
        'end' => date("Y-m-t", strtotime('last month'))
    );

    $countLast = mysql_select_single("SELECT COUNT(*) as `total_usuarios` FROM `usuarios` INNER JOIN `contas` ON `usuarios`.`conta_id` = `contas`.`id` where `contas`.`criacao`>='".$dateLast['start']."' and `contas`.`criacao`<='".$dateLast['end']."' and `contas`.`nv_acesso`=0 ;");



    if($countLast['total_usuarios'] != 0)
        $count = ($countActual['total_usuarios'] - $countLast['total_usuarios']) / $countLast['total_usuarios'] * 100;

    return number_format($count, 2);
}

/*
getDashBoardGraphicSchedules();
Descrição: Retorna com o calculo da diferença entre o mês anterior e atual dos Agendamentos
*/
function getDashBoardGraphicSchedules() {
    $count = 0;
    
    /* INFORMAÇÕES DO MÊS ATUAL */
    $dateActual = array(
        'start' => date("Y-m-01"),
        'end' => date("Y-m-t")
    );

    $countActual = mysql_select_single("SELECT COUNT(*) as `total_agendamentos` FROM `agendamentos` where `data_agendada`>='".$dateActual['start']."' and `data_agendada`<='".$dateActual['end']."';");

    /* INFORMAÇÕES DO MÊS PASSADO */
    $dateLast = array(
        'start' => date("Y-m-01", strtotime('last month')),
        'end' => date("Y-m-t", strtotime('last month'))
    );

    $countLast = mysql_select_single("SELECT COUNT(*) as `total_agendamentos` FROM `agendamentos` where `data_agendada`>='".$dateLast['start']."' and `data_agendada`<='".$dateLast['end']."';");

    if($countLast['total_agendamentos'] != 0)
        $count = ($countActual['total_agendamentos'] - $countLast['total_agendamentos']) / $countLast['total_agendamentos'] * 100;

    return number_format($count, 2);
}



/*
getDashBoardGraphicPays();
Descrição: Retorna com o calculo da diferença entre o mês anterior e atual dos Pagamentos
*/
function getDashBoardGraphicPays() {
    $count = 0;

    /* INFORMAÇÕES DO MÊS ATUAL */
    $dateActual = array(
        'start' => date("Y-m-01"),
        'end' => date("Y-m-t")
    );

    $countActual = mysql_select_single("SELECT SUM(`valor_total`) as `total_pagamentos` FROM `pagamentos` where `data_pagamento`>='".$dateActual['start']."' and `data_pagamento`<='".$dateActual['end']."' and `tipo_pagamento` > 0;");
    $cashActual = mysql_select_single("SELECT SUM(`valor_fechamento`) as `total_caixa` FROM `caixa` where `data_abertura`>='".$dateActual['start']."' and `data_abertura`<='".$dateActual['end']."' and `status`=0;");
    $cA = (int)$countActual['total_pagamentos'] + (int)$cashActual['total_caixa'];

    
    /* INFORMAÇÕES DO MÊS PASSADO */
    $dateLast = array(
        'start' => date("Y-m-01", strtotime('last month')),
        'end' => date("Y-m-t", strtotime('last month'))
    );

    $countLast = mysql_select_single("SELECT SUM(`valor_total`) as `total_pagamentos` FROM `pagamentos` where `data_pagamento`>='".$dateLast['start']."' and `data_pagamento`<='".$dateLast['end']."' and `tipo_pagamento` > 0;");
    $cashLast = mysql_select_single("SELECT SUM(`valor_fechamento`) as `total_caixa` FROM `caixa` where `data_abertura`>='".$dateLast['start']."' and `data_abertura`<='".$dateLast['end']."' and `status`=0;");

    $cL = (int)$countLast['total_pagamentos'] + (int)$cashLast['total_caixa'];

    if($cL > 0)
        $count = ($cA - $cL) / $cL * 100;


    return number_format($count, 2);
}


/*
getDashBoardValue($value);
Descrição: Verifica o valor de entrada e retorna com positive se true ou negative se false
*/
function getDashBoardValue($value) {

    return ($value > 0) ? 'positive' : 'negative'; 
}


/*
getDashBoardValue($value);
Descrição: Verifica o valor de entrada e retorna com up se true ou down se false
*/
function getDashBoardArrow($value) {

    return ($value > 0) ? 'up' : 'down'; 
}



?>