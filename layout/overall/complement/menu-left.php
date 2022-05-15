<script>
	
			function activeOptionNav(cla) {
            const c = document.querySelectorAll('.optionsNav');
                c.forEach(el => el.classList.toggle('selectNav', el === cla));
                
            }

			$(function(){
                $("#mn-navSelect").click(function(e) {
					$("#menuLeft").toggleClass("activeMenuLF")

					$('#registerNav').toggle();
					$('#servicesNav').toggle();

					$('#paysNav').toggle();
					$('#adminNav').toggle();
					$('#exit-button').toggle();

					if(document.getElementById("mn-welcome").style.display != 'flex') {
                    	document.getElementById("mn-welcome").style.display = 'flex';
					}
					else {
                    	document.getElementById("mn-welcome").style.display = 'none';
					}
					$('#registerOptions').hide();					
					$('#servicesOptions').hide();					

					$('#paysOptions').hide();
					$('#adminOptions').hide();
                });

                $("#registerNav").click(function(e) {
					$('#registerOptions').show();
					$('#servicesOptions').hide();
					$('#paysOptions').hide();
					$('#adminOptions').hide();
					$(".pac").addClass("activeOption");
					$(".serv").removeClass("activeOption");
					$(".pays").removeClass("activeOption");
					$(".admin").removeClass("activeOption");
                });

                $("#servicesNav").click(function(e) {
					$('#registerOptions').hide();
					$('#servicesOptions').show();
					$('#paysOptions').hide();
					$('#adminOptions').hide();
					$(".pac").removeClass("activeOption");
					$(".serv").addClass("activeOption");
					$(".pays").removeClass("activeOption");
					$(".admin").removeClass("activeOption");
                });

                $("#paysNav").click(function(e) {
					$('#registerOptions').hide();
					$('#servicesOptions').hide();
					$('#paysOptions').show();
					$('#adminOptions').hide();
					$(".pac").removeClass("activeOption");
					$(".serv").removeClass("activeOption");
					$(".pays").addClass("activeOption");
					$(".admin").removeClass("activeOption");
                });

                $("#adminNav").click(function(e) {
					$('#registerOptions').hide();
					$('#servicesOptions').hide();
					$('#paysOptions').hide();
					$('#adminOptions').show();
					$(".pac").removeClass("activeOption");
					$(".serv").removeClass("activeOption");
					$(".pays").removeClass("activeOption");
					$(".admin").addClass("activeOption");
                });
            });
</script>	
<div id="menuLeft" class="activeMenuLF">
	<div id="mn-navSelect">
		<i class="fa fa-bars" aria-hidden="true"></i>
	</div>
	<div id="mn-welcome">
		<div id="mn-profileIMG">
			<a href="myaccount.php">
				<img src="<?php echo getUserFolderForIMG(getUserCPF((int)$user_data['id'])).'/'.getUserPhotoName((int)$user_data['id']); ?>"></img>
			</a>
		</div>&nbsp;&nbsp;
		<div id="mn-profileMSG">
			Seja bem vindo,<br/><b><?php echo ucfirst(strtolower(getUserFirstName($user_data['id']))); ?></b>
		</div>
	</div>
	
	<?php
	if(isAttendant($user_data['id']) || isManager($user_data['id']) || isAdmin($user_data['id'])) { 
	?>
	<li id="registerNav" class="optionsNav" onclick="activeOptionNav(this);">
		<i class="fa fa-users fa-background pac" aria-hidden="true"></i>
		Paciêntes
	</li>
	<nav id="registerOptions">
		<ul>
			<a href="registerUsers.php">
				<li><i class="fa fa-users fa-background" aria-hidden="true"></i>  Cadastrar Paciente</li>
			</a>
			<a href="usersList.php">
				<li><i class="fa fa-users fa-background" aria-hidden="true"></i>  Lista de Paciêntes</li>
			</a>
		</ul>
	</nav>

	<li id="servicesNav" class="optionsNav" onclick="activeOptionNav(this);">
		<i class="fa fa-users fa-background serv" aria-hidden="true"></i>
		Agenda
	</li>
	<nav id="servicesOptions">
		<ul>
			<a href="schedulingList.php">
				<li><i class="fa fa-users" aria-hidden="true"></i>  Agendamentos</li>
			</a>
		</ul>
	</nav>

	<li id="paysNav" class="optionsNav" onclick="activeOptionNav(this);">
		<i class="fa fa-users fa-background pays" aria-hidden="true"></i>
		Caixa
	</li>
	<nav id="paysOptions">
		<ul>
			<a href="cashdesk.php">
				<li><i class="fa fa-users" aria-hidden="true"></i>  Abrir/Fechar Caixa</li>
			</a>
			<a href="cashierHistory.php">
				<li><i class="fa fa-users" aria-hidden="true"></i>  Histórico de Pagamentos</li>
			</a>
			<a href="cashierOpeningHistory.php">
				<li><i class="fa fa-users" aria-hidden="true"></i>  Historico de Caixa</li>
			</a>
		</ul>
	</nav>
	<?php
	} 
	if(isAdmin($user_data['id'])) { 
	?>
	<li id="adminNav" class="optionsNav" onclick="activeOptionNav(this);">
		<i class="fa fa-users fa-background admin" aria-hidden="true"></i>
		Configurações
	</li>
	<nav id="adminOptions">
		<ul>
			<a href="pathology.php">
				<li><i class="fa fa-users" aria-hidden="true"></i>  Patologias</li>
			</a>
			<a href="services.php">
				<li><i class="fa fa-users" aria-hidden="true"></i>  Serviços</li>
			</a>
		</ul>
	</nav>
	<?php
	}
	
	?>
	
	<a href="logout.php">
		<li id="exit-button">Sair</li>
	</a>
</div>
