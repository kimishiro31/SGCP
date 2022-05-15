<?php 
require_once 'engine/init.php';
$actualDate = date("Y-m-d");
doProtect();
$titlepage = "Controle de Usuário";
include 'layout/overall/header.php';

if(isAttendant($user_data['id']) || isManager($user_data['id']) || isAdmin($user_data['id'])) { 
?>

	<div id="db-Container">
		<div id="dbValues-Container">
			<div class="dbValues-Frame clientsFrame">
				<li class="dbTitle-Frame">Pacientes <i class="fa fa-users" aria-hidden="true"></i></li>
				<li class="dbValue-Frame"><?php echo doTotalUsersCommon() ?></li>
				<div class="dbStatus-Frame">
					<li class="dbstatus <?php echo getDashBoardValue(getDashBoardGraphicPatients()); ?>">
						<i class="fa fa-arrow-<?php echo getDashBoardArrow(getDashBoardGraphicPatients()); ?>" aria-hidden="true"></i><?php echo getDashBoardGraphicPatients(); ?>%
					</li>
					<li class="dbTime">Nos ultimos 30 dias</li>
				</div>
			</div>
			<div class="dbValues-Frame attendanceFrame">
				<li class="dbTitle-Frame">Agendamentos <i class="fa fa-users" aria-hidden="true"></i></li>
				<li class="dbValue-Frame"><?php echo getTotalScheduling() ?></li>
				<div class="dbStatus-Frame">
					<li class="dbstatus <?php echo getDashBoardValue(getDashBoardGraphicSchedules()); ?>">
						<i class="fa fa-arrow-<?php echo getDashBoardArrow(getDashBoardGraphicSchedules()); ?>" aria-hidden="true"></i><?php echo getDashBoardGraphicSchedules(); ?>%
					</li>
					<li class="dbTime">Nos ultimos 30 dias</li>
				</div>
			</div>
			<div class="dbValues-Frame paysFrame">
				<li class="dbTitle-Frame">Pagamentos <i class="fa fa-users" aria-hidden="true"></i></li>
				<li class="dbValue-Frame">R$ <?php echo getCashierBalanceMonth(); ?></li>
				<div class="dbStatus-Frame">
				<li class="dbstatus <?php echo getDashBoardValue(getDashBoardGraphicPays()); ?>">
						<i class="fa fa-arrow-<?php echo getDashBoardArrow(getDashBoardGraphicPays()); ?>" aria-hidden="true"></i><?php echo getDashBoardGraphicPays(); ?>%
					</li>
					<li class="dbTime">Nos ultimos 30 dias</li>
				</div>
			</div>
			<div style="display: none" class="dbValues-Frame test3">
				<li class="dbTitle-Frame">Text <i class="fa fa-users" aria-hidden="true"></i></li>
				<li class="dbValue-Frame">35.000</li>
				<div class="dbStatus-Frame">
					<li class="dbstatus positive">
						<i class="fa fa-arrow-up" aria-hidden="true"></i>   5,27%
					</li>
					<li class="dbTime">Nos ultimos 30 dias</li>
				</div>
			</div>
		</div>
		<div id="dbHours-Container">
			<div class="dbHours-Content dbHours-Test">
				<li class="dbHours-Title">Horários Disponiveis</li>
				<div class="dbHours-Frame">
					<table class="dbHours-Table">
						<tr>
							<th>Data</th>
							<th>Horário</th>
						</tr>
						<?php
						$hours = doListHoursAvailable($actualDate);
						if($hours !== false) {
							foreach($hours as $key => $value) {
						?>
								<tr style="cursor: context-menu;">
									<td><?php echo doDateConvert($actualDate); ?></td>
									<td><?php echo $value; ?></td>
								</tr>
						<?php
							}
						}
						?>

					</table>
				</div>
			</div>
			<div class="dbHours-Content dbHours-Test1">
				<li class="dbHours-Title">Horários Agendados(HOJE)</li>
				<div class="dbHours-Frame">
					<table class="dbHours-Table">
						<tr>
							<th>Data</th>
							<th>Horário</th>
							<th>Paciente</th>
						</tr>
						<?php
						$hours = doListHoursScheduled($actualDate);
						if($hours !== false) {
							foreach($hours as $key => $value) {
						?>
								<tr  onclick="location.href='usersdata.php?subpage=schedulingEdit&data=<?php echo $value['usuario_id']?>&scheduling=<?php echo $value['id']?>'">
									<td><?php echo doDateConvert($actualDate); ?></td>
									<td><?php echo $value['hora_agendada']; ?></td>
									<td><?php echo getUserCompleteName($value['usuario_id']); ?></td>
								</tr>
						<?php
							}
						}
						?>
					</table>
				</div>
			</div>
		</div>
	</div>


<?php
}
include 'layout/overall/footer.php';
?>