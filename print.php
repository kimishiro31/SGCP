<?php 
	require_once 'engine/init.php';
	$validator = isUserExist($_GET['data']);

	$user_data = getUsersData($validator, 'CONTA_ID', 'PRIMEIRO_NOME', 'ULTIMO_NOME', 'DATA_NASCIMENTO', 'CPF', 'RG', 'GENERO', 'RUA', 'BAIRRO', 'NUMERO', 'COMPLEMENTO', 'CEP', 'CIDADE', 'ESTADO', 'NACIONALIDADE', 'PROFISSAO', 'FOTO');
	$account_data = getAccountData($user_data['CONTA_ID'], 'usuario', 'email', 'telefone', 'telefone_02', 'criacao', 'NV_ACESSO', 'status');

	//referenciar o DomPDF com namespace
	use Dompdf\Dompdf;
	use Dompdf\Options;
	use Dompdf\Image;

	// INSTANCIA DE OPTIONS
	$options = new Options();
	$options->setChroot(__DIR__);
	$options->setIsRemoteEnabled(true);
	
	// INSTANCIA de DomPDF
	$dompdf = new DomPdf($options);
	
	$r10 = (getUserAnswerAnswerID($validator, 1) == getAnswerQuestionID(1, 0)) ? 'checked' : '';
	$r11 = (getUserAnswerAnswerID($validator, 1) == getAnswerQuestionID(1, 1)) ? 'checked' : '';
	$r1OBS = getUserAnswerDescription($validator, 1);
	
	$r20 = (getUserAnswerAnswerID($validator, 2) == getAnswerQuestionID(2, 0)) ? 'checked' : '';
	$r21 = (getUserAnswerAnswerID($validator, 2) == getAnswerQuestionID(2, 1)) ? 'checked' : '';
	
	$r30 = (getUserAnswerAnswerID($validator, 3) == getAnswerQuestionID(3, 0)) ? 'checked' : '';
	$r31 = (getUserAnswerAnswerID($validator, 3) == getAnswerQuestionID(3, 1)) ? 'checked' : '';
	$r3OBS = (getUserAnswerAnswerID($validator, 3) == getAnswerQuestionID(3, 0)) ? getUserAnswerDescription($validator, 3) : '';
	
	$r40 = (getUserAnswerAnswerID($validator, 4) == getAnswerQuestionID(4, 0)) ? 'checked' : '';
	$r41 = (getUserAnswerAnswerID($validator, 4) == getAnswerQuestionID(4, 1)) ? 'checked' : '';
	$r4OBS = (getUserAnswerAnswerID($validator, 4) == getAnswerQuestionID(4, 0)) ? getUserAnswerDescription($validator, 4) : '';
	
	$r50 = (getUserAnswerAnswerID($validator, 5) == getAnswerQuestionID(5, 0)) ? 'checked' : '';
	$r51 = (getUserAnswerAnswerID($validator, 5) == getAnswerQuestionID(5, 1)) ? 'checked' : '';
	$r5OBS = (getUserAnswerAnswerID($validator, 5) == getAnswerQuestionID(5, 0)) ? getUserAnswerDescription($validator, 5) : '';
		
	$r60 = (getUserAnswerAnswerID($validator, 6) == getAnswerQuestionID(6, 0)) ? 'checked' : '';
	$r61 = (getUserAnswerAnswerID($validator, 6) == getAnswerQuestionID(6, 1)) ? 'checked' : '';
	$r6OBS = (getUserAnswerAnswerID($validator, 6) == getAnswerQuestionID(6, 0)) ? getUserAnswerDescription($validator, 6) : '';
	
	$r70 = (getUserAnswerAnswerID($validator, 7) == getAnswerQuestionID(7, 0)) ? 'checked' : '';
	$r71 = (getUserAnswerAnswerID($validator, 7) == getAnswerQuestionID(7, 1)) ? 'checked' : '';
	$r7OBS = (getUserAnswerAnswerID($validator, 7) == getAnswerQuestionID(7, 0)) ? getUserAnswerDescription($validator, 7) : '';
	
	$r80 = (getUserAnswerAnswerID($validator, 8) == getAnswerQuestionID(8, 0)) ? 'checked' : '';
	$r81 = (getUserAnswerAnswerID($validator, 8) == getAnswerQuestionID(8, 1)) ? 'checked' : '';
	$r8OBS = (getUserAnswerAnswerID($validator, 8) == getAnswerQuestionID(8, 0)) ? getUserAnswerDescription($validator, 8) : '';
	
	$r90 = (getUserAnswerAnswerID($validator, 9) == getAnswerQuestionID(9, 0)) ? 'checked' : '';
	$r91 = (getUserAnswerAnswerID($validator, 9) == getAnswerQuestionID(9, 1)) ? 'checked' : '';
	
	$r100 = (getUserAnswerAnswerID($validator, 10) == getAnswerQuestionID(10, 0)) ? 'checked' : '';
	$r101 = (getUserAnswerAnswerID($validator, 10) == getAnswerQuestionID(10, 1)) ? 'checked' : '';
		
	$r110 = (getUserAnswerAnswerID($validator, 11) == getAnswerQuestionID(11, 0)) ? 'checked' : '';
	$r111 = (getUserAnswerAnswerID($validator, 11) == getAnswerQuestionID(11, 1)) ? 'checked' : '';
	
	$r120 = (getUserAnswerAnswerID($validator, 12) == getAnswerQuestionID(12, 0)) ? 'checked' : '';
	$r121 = (getUserAnswerAnswerID($validator, 12) == getAnswerQuestionID(12, 1)) ? 'checked' : '';
	
	$r130 = (getUserAnswerAnswerID($validator, 13) == getAnswerQuestionID(13, 0)) ? 'checked' : '';
	$r131 = (getUserAnswerAnswerID($validator, 13) == getAnswerQuestionID(13, 1)) ? 'checked' : '';
	
	$r140 = (getUserAnswerAnswerID($validator, 14) == getAnswerQuestionID(14, 0)) ? 'checked' : '';
	$r141 = (getUserAnswerAnswerID($validator, 14) == getAnswerQuestionID(14, 1)) ? 'checked' : '';
	
	$r150 = (getUserAnswerAnswerID($validator, 15) == getAnswerQuestionID(15, 0)) ? 'checked' : '';
	$r151 = (getUserAnswerAnswerID($validator, 15) == getAnswerQuestionID(15, 1)) ? 'checked' : '';
	
	$r160 = (getUserAnswerAnswerID($validator, 16) == getAnswerQuestionID(16, 0)) ? 'checked' : '';
	$r161 = (getUserAnswerAnswerID($validator, 16) == getAnswerQuestionID(16, 1)) ? 'checked' : '';
	
	$pat1VP = getPathologyName(getUserPathologyPos($validator, 1, 'VP'));
	$pos1VP = ($pat1VP) ? 'na posição <b>'.getUserPathologyPos($validator, 1, 'VP').'</b>' : '--';

	$pat2VP = getPathologyName(getUserPathologyPos($validator, 2, 'VP'));
	$pos2VP = ($pat2VP) ? 'na posição <b>'.getUserPathologyPos($validator, 2, 'VP').'</b>' : '--';

	$pat3VP = getPathologyName(getUserPathologyPos($validator, 3, 'VP'));
	$pos3VP = ($pat3VP) ? 'na posição <b>'.getUserPathologyPos($validator, 3, 'VP').'</b>' : '--';

	$pat4VP = getPathologyName(getUserPathologyPos($validator, 4, 'VP'));
	$pos4VP = ($pat4VP) ? 'na posição <b>'.getUserPathologyPos($validator, 4, 'VP').'</b>' : '--';

	$pat5VP = getPathologyName(getUserPathologyPos($validator, 5, 'VP'));
	$pos5VP = ($pat5VP) ? 'na posição <b>'.getUserPathologyPos($validator, 5, 'VP').'</b>' : '--';
	
	$pat6VP = getPathologyName(getUserPathologyPos($validator, 6, 'VP'));
	$pos6VP = ($pat6VP) ? 'na posição <b>'.getUserPathologyPos($validator, 6, 'VP').'</b>' : '--';

	$pat7VP = getPathologyName(getUserPathologyPos($validator, 7, 'VP'));
	$pos7VP = ($pat7VP) ? 'na posição <b>'.getUserPathologyPos($validator, 7, 'VP').'</b>' : '--';

	$pat8VP = getPathologyName(getUserPathologyPos($validator, 8, 'VP'));
	$pos8VP = ($pat8VP) ? 'na posição <b>'.getUserPathologyPos($validator, 8, 'VP').'</b>' : '--';

	$pat9VP = getPathologyName(getUserPathologyPos($validator, 9, 'VP'));
	$pos9VP = ($pat9VP) ? 'na posição <b>'.getUserPathologyPos($validator, 9, 'VP').'</b>' : '--';

	$pat10VP = getPathologyName(getUserPathologyPos($validator, 10, 'VP'));
	$pos10VP = ($pat10VP) ? 'na posição <b>'.getUserPathologyPos($validator, 10, 'VP').'</b>' : '--';
	

	$pat1VD = getPathologyName(getUserPathologyPos($validator, 1, 'VD'));
	$pos1VD = ($pat1VD) ? 'na posição <b>'.getUserPathologyPos($validator, 1, 'VD').'</b>' : '--';

	$pat2VD = getPathologyName(getUserPathologyPos($validator, 2, 'VD'));
	$pos2VD = ($pat2VD) ? 'na posição <b>'.getUserPathologyPos($validator, 2, 'VD').'</b>' : '--';

	$pat3VD = getPathologyName(getUserPathologyPos($validator, 3, 'VD'));
	$pos3VD = ($pat3VD) ? 'na posição <b>'.getUserPathologyPos($validator, 3, 'VD').'</b>' : '--';

	$pat4VD = getPathologyName(getUserPathologyPos($validator, 4, 'VD'));
	$pos4VD = ($pat4VD) ? 'na posição <b>'.getUserPathologyPos($validator, 4, 'VD').'</b>' : '--';

	$pat5VD = getPathologyName(getUserPathologyPos($validator, 5, 'VD'));
	$pos5VD = ($pat5VD) ? 'na posição <b>'.getUserPathologyPos($validator, 5, 'VD').'</b>' : '--';
	
	$pat6VD = getPathologyName(getUserPathologyPos($validator, 6, 'VD'));
	$pos6VD = ($pat6VD) ? 'na posição <b>'.getUserPathologyPos($validator, 6, 'VD').'</b>' : '--';

	$pat7VD = getPathologyName(getUserPathologyPos($validator, 7, 'VD'));
	$pos7VD = ($pat7VD) ? 'na posição <b>'.getUserPathologyPos($validator, 7, 'VD').'</b>' : '--';

	$pat8VD = getPathologyName(getUserPathologyPos($validator, 8, 'VD'));
	$pos8VD = ($pat8VD) ? 'na posição <b>'.getUserPathologyPos($validator, 8, 'VD').'</b>' : '--';

	$pat9VD = getPathologyName(getUserPathologyPos($validator, 9, 'VD'));
	$pos9VD = ($pat9VD) ? 'na posição <b>'.getUserPathologyPos($validator, 9, 'VD').'</b>' : '--';

	$pat10VD = getPathologyName(getUserPathologyPos($validator, 10, 'VD'));
	$pos10VD = ($pat10VD) ? 'na posição <b>'.getUserPathologyPos($validator, 10, 'VD').'</b>' : '--';
	
















	// CARREGA O HTML PARA DENTRO DA CLASSE
	$dompdf->loadHtml('

			<html lang="pt-BR" dir="ltr">
				<head>
					<meta charset="utf-8">
					<title>Ficha Anamnese</title>
					<style>
					.clear {
						clear: both;
					}
					
					.border { 
						border: 1px solid black;
					}
					
					@media print {
						.page-break { 
							height: 0; 
							page-break-before: always; 
							margin: 0; 
							border-top: none; 
						}
					}

					body {
						margin-left: 2em; 
						margin-right: 2em;
						border: 2px solid black;
					}

					.page {
						height: 947px;
						padding-top: 5px;
						page-break-after: always;   
						font-family: Arial, Helvetica, sans-serif;
						position: relative;
						border-bottom: 1px solid #000;
					}

					#section1 {
						overflow: hidden;
						word-break: break-all;
						width: 100%;
					}

					#header {
						width: 70%;
						margin: 0 auto;
						
					}

					#logo {
						width: 150px;
						position: relative;
						margin: auto;
						float: left;
					}

					#logo img {
						width: 100%;
					}

					#text {
						margin: auto;
						text-align: center;
						float: left;
					}
					
					#typeClinic {
						font-size: 70px;
						font-family: cursive;
					}


					#typeData {
						font-size: 30px;
					}


					#body {
						width: 100%;
					}

					#dataPersonal tr td {
						border-bottom: 1px solid;
						font-size: 15px;
						font-family: serif;
					}
							
					.txtOthers {
						width: 280px;
						border: 1px solid black;
						border-radius: 10px;
						padding: 5px 10px;
					}
					
							
					table tr td{
						padding: 0px 10px;
					}
					
							
					#obsProfissional tr td {
						font-size: 9px;
					}
					
					.page-break { 
						page-break-before: always; 
					}
									
					#plantar, #dorsal {
						width: 450px;
						margin: auto;
					}

					#plantar img, #dorsal img  {
						width: 100%;
					}
						
					</style>


					
				</head>

				<body>
					<div class="Section1"> 
						<div id="header">
							<div id="logo">
								<img src="https://i.pinimg.com/originals/74/e7/3e/74e73e1d80a9a3e05692c109ac883f58.png"/>
							</div>
							<div id="text">
								<label id="typeClinic">Podologia</label><br>
								<label id="typeData">Ficha Anamnese</label>
							</div>
							<div class="clear"></div>
						</div>
						<div class="border"></div>

						<div id="body">
							<table width="100%" id="dataPersonal">
								<tr>
									<td>
										Nome: '.$user_data['PRIMEIRO_NOME'].' '.$user_data['ULTIMO_NOME'].'
									</td>
									<td>
										Data Nasc.: '.doDateConvert($user_data['DATA_NASCIMENTO']).'
									</td>
								</tr>
								<tr>
									<td>
										Ocupação: '.$user_data['PROFISSAO'].'
									</td>
									<td>
										Fone/Cel.: '.$account_data['telefone'].'
									</td>
								</tr>
								<tr>
									<td colspan="2">
										Endereço: '.$user_data['RUA'].' Nº '.$user_data['NUMERO'].' '.$user_data['COMPLEMENTO'].', BAIRRO '.$user_data['BAIRRO'].' '.$user_data['CIDADE'].'/'.$user_data['ESTADO'].'
									</td>
								</tr>
								<tr>
									<td>
										RG: '.$user_data['RG'].'
									</td>
									<td>
										CPF: '.doFormatCPF($user_data['CPF']).'
									</td>
								</tr>
							</table>
							<table width="100%" id="dataClinic">
							<tr>
								<td colspan="1">
									Tipo de calçado mais utilizado:
									<div class="txtOthers">
										<input type="checkbox" '.$r10.'/> Aberto |
										<input type="checkbox" '.$r11.'/> Fechado | 
											Nº '.$r1OBS.'
									</div>
								</td>
								
								<Tipo colspan="1">
									Tipo de meia mais utilizado:
									<div class="txtOthers">
										<input type="checkbox" '.$r20.'/> Social |
										<input type="checkbox" '.$r21.'/> Esportiva
									</div>
								</td>
							</tr>
							<tr>
								<td>
									Cirurgia nos membros inferores?
									<div class="txtOthers">
										<input type="checkbox" '.$r30.'/> Sim |
										<input type="checkbox" '.$r31.'/> Não |
										Especifique: '.$r3OBS.'<br/>
									</div>
								</td>
								<td>
									Prática esportes?
									<div class="txtOthers">
										<input type="checkbox" '.$r40.'/> Sim |
										<input type="checkbox" '.$r41.'/> Não |
										Especifique: '.$r4OBS.'<br/>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									Está tomando algum medicamento?
									<div class="txtOthers">
										<input type="checkbox" '.$r50.'/> Sim |
										<input type="checkbox" '.$r51.'/> Não |
										Especifique: '.$r5OBS.'<br/>
									</div>
									</div>
								</td>
								<td>
									Gestante?
									<div class="txtOthers">
										<input type="checkbox" '.$r60.'/> Sim |
										<input type="checkbox" '.$r61.'/> Não |
										Especifique: '.$r6OBS.'<br/>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									Possui alguma alergia?
									<div class="txtOthers">
										<input type="checkbox" '.$r70.'/> Sim |
										<input type="checkbox" '.$r71.'/> Não |
										Especifique: '.$r7OBS.'<br/>
									</div>
								</td>
								<td>
									Sensibilidade a dor?
									<div class="txtOthers">
										<input type="checkbox" '.$r80.'/> Sim |
										<input type="checkbox" '.$r81.'/> Não |
										Especifique: '.$r8OBS.'<br/>

									</div>
								</td>
							</tr>
							<tr>
								<td>
									<b>Tem hipo/hipertensão arterial?</b>
									<div class="options">
										<input type="checkbox" '.$r90.'/> Sim |
										<input type="checkbox" '.$r91.'/> Não 
									</div>
								</td>
								<td>
									<b>Diabetes?</b>
									<div class="options">
										<input type="checkbox" '.$r100.'/> Sim |
										<input type="checkbox" '.$r101.'/> Não 
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<b>Hanseniase?</b>
									<div class="options">
										<input type="checkbox" '.$r110.'/> Sim |
										<input type="checkbox" '.$r111.'/> Não 
									</div>
								</td>
								<td>
									<b>Cardiopatia?</b>
									<div class="options">
										<input type="checkbox" '.$r120.'/> Sim |
										<input type="checkbox" '.$r121.'/> Não 
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<b>Algum tipo de câncer?</b>
									<div class="options">
										<input type="checkbox" '.$r130.'/> Sim |
										<input type="checkbox" '.$r131.'/> Não 
									</div>
								</td>
								<td>
									<b>Portador de marcapasso/pinos?</b>
									<div class="options">
										<input type="checkbox" '.$r140.'/> Sim |
										<input type="checkbox" '.$r141.'/> Não 
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<b>Distúrbio circulatório?</b>
									<div class="options">
										<input type="checkbox" '.$r150.'/> Sim |
										<input type="checkbox" '.$r151.'/> Não 
									</div>
								</td>
								<td>
									<b>Hepatite?</b>
									<div class="options">
										<input type="checkbox" '.$r160.'/> Sim |
										<input type="checkbox" '.$r161.'/> Não 
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<br>		
									<center>
										Declaro que as informações acima são verdadeiras, não cabendo ao profissional quaisquer responsabilidades por informações omitidas nessa avaliaçaõ. Tenho ciência e estou de acordo a respeito dos procedimentos envolvidos.
									</center>
								</td>
							</tr>
							<tr>
								<td><br>
									Data: ______/__________/________
								</td>
								<td><br>
									Assinatura: __________________________
								</td>
							</tr>
						</table>
					</div>


					
					<div class="page-break">
						<table width="100%" id="obs-table">
							<tr>
								<th colspan="2">
									<b>OBSERVAÇÕES PROFISSIONAIS:</b>
								</th>
							</tr>
							<tr>
								<td colspan="2">
									<div id="plantar">
										<img src="layout\images\vp.png"/>
									</div>
								</td>
							</tr>
							<tr>
								<th>
									<b>PATOLOGIAS PÉ ESQUERDO:</b>
								</th>
								<th>
									<b>PATOLOGIAS PÉ DIREITO:</b>
								</th>
							</tr>

							<tr style="text-align: center;">   
								<td>1. <b>'.$pat1VP.'</b> '.$pos1VP.'</td>
								<td>2. <b>'.$pat6VP.'</b> '.$pos6VP.'</td>
							</tr>
							<tr style="text-align: center;">   
								<td>2. <b>'.$pat2VP.'</b> '.$pos2VP.'</td>
								<td>3. <b>'.$pat7VP.'</b> '.$pos7VP.'</td>
							</tr>
							<tr style="text-align: center;">   
								<td>3. <b>'.$pat3VP.'</b> '.$pos3VP.'</td>
								<td>4. <b>'.$pat8VP.'</b> '.$pos8VP.'</td>
							</tr>
							<tr style="text-align: center;">   
								<td>4. <b>'.$pat4VP.'</b> '.$pos4VP.'</td>
								<td>5. <b>'.$pat9VP.'</b> '.$pos9VP.'</td>
							</tr>
							<tr style="text-align: center;">   
								<td>5. <b>'.$pat5VP.'</b> '.$pos5VP.'</td>
								<td>6. <b>'.$pat10VP.'</b> '.$pos10VP.'</td>
							</tr>
						</table>
						<br/>
						<div class="border"></div>
						<br/>

						<table width="100%" id="obs-table">
							<tr>
								<td colspan="2">
									<div id="plantar">
										<img src="layout\images\vd.png"/>
									</div>
								</td>
							</tr>
							<tr>
								<th>
									<b>PATOLOGIAS PÉ ESQUERDO:</b>
								</th>
								<th>
									<b>PATOLOGIAS PÉ DIREITO:</b>
								</th>
							</tr>

							<tr style="text-align: center;">   
								<td>1. <b>'.$pat1VD.'</b> '.$pos1VD.'</td>
								<td>2. <b>'.$pat6VD.'</b> '.$pos6VD.'</td>
							</tr>
							<tr style="text-align: center;">   
								<td>2. <b>'.$pat2VD.'</b> '.$pos2VD.'</td>
								<td>3. <b>'.$pat7VD.'</b> '.$pos7VD.'</td>
							</tr>
							<tr style="text-align: center;">   
								<td>3. <b>'.$pat3VD.'</b> '.$pos3VD.'</td>
								<td>4. <b>'.$pat8VD.'</b> '.$pos8VD.'</td>
							</tr>
							<tr style="text-align: center;">   
								<td>4. <b>'.$pat4VD.'</b> '.$pos4VD.'</td>
								<td>5. <b>'.$pat9VD.'</b> '.$pos9VD.'</td>
							</tr>
							<tr style="text-align: center;">   
								<td>5. <b>'.$pat5VD.'</b> '.$pos5VD.'</td>
								<td>6. <b>'.$pat10VD.'</b> '.$pos10VD.'</td>
							</tr>
						</table>
					</div>
				</body>
			</html>	
	
	');
	
	// RENDERIZA O ARQUIVO PDF
	$dompdf->render();
	
	header('Content-type: application/pdf');
	
	echo $dompdf->output();
?>