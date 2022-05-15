
<?php 
if (getLoggedIn()) :
?>
<center>
<div id="panelAccount">
	<table>
		<tr>
			<td>
				<a href="myaccount.php">
					<button class="otherButton" type="submit">Painel</button>
				</a>
			</td>
			<td>
				<a href="createcharacter.php">
					<button class="otherButton" type="submit">Personagem</button>
				</a>
			</td>
			<td>
				<a href="settings.php">
					<button class="otherButton" type="submit">Informação</button>
				</a>
			</td>
			<td>
				<a href="changepassword.php">
					<button class="otherButton" type="submit">Configurações</button>
				</a>
			</td>
			<td>
				<a href="logout.php">
					<button class="otherButton" type="submit">Sair</button>
				</a>
			</td>
	<?php 
		if (getLoggedIn()) {
			if (getUserIsAdmin($user_data)) {
	?>
			<td>
				<a href="admin.php">
					<button class="otherButton" type="submit">Administração</button>
				</a>
			</td>
	<?php
			}
		}
	?>

		</tr>
	</table>
</div>
</center>
<?php 
else:
?>
<article id="menuBox">
	<form action="login.php?subpage=validation" method="post">
		<input name="username" class="smallInput" placeholder="Usuário" type="text"></input>
		<input name="password" class="smallInput" placeholder="senha" type="password"></input>
		<button class="enterButton" type="image">Entrar</button>
		<?php echo setRECaptcha(); ?>
	</form>
</article>
<?php 
endif;
?>

