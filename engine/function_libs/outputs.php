<?php

// output_msgs($msgs)
function output_errors($errors) {
	return '
		<script>
			function closerror(){
				document.getElementById("box-error").style.display =  "none";
			}
			setTimeout(function () {
				document.getElementById("box-error").style.display =  "none";
			}, 5 * 1000);
		</script>
		<div id="box-error">
			<center>
				<h2>ERRO!!</h2>
			</center>

			<button type="button" onclick="javascript: closerror();" id="box-close">X</button>
			<center>'.implode('</br>', $errors).'</center>
		</div>
		';
}

// output_msgs($msgs)
function output_warning($warning) {
	echo '
	<script>
		function closewarning(){
			document.getElementById("box-warning").style.display =  "none";
		}
		setTimeout(function () {
			document.getElementById("box-error").style.display =  "none";
		}, 5 * 1000);
	</script>';
	return '
		<div id="box-warning">
			<center>
				<h2>AVISO!!</h2>
			</center>
			<button type="button" onclick="javascript: closewarning();" id="box-close">X</button>
			<center>'.$warning.'</center>
		</div>';
}

// output_msgs($msgs)
function output_msgs($msgs, $refresh = false) {
	if($refresh !== false) 
		$ex = '
			setTimeout(function() {
				window.location.href = "'.$refresh.'";
			}, 1000);
		';
	else
		$ex = '';


	return '<script>
				function closesuccess(){
					document.getElementById("box-success").style.display =  "none";
				}
				setTimeout(function () {
					document.getElementById("box-success").style.display =  "none";
				}, 5 * 1000);
			'.$ex.'
			</script>
			<div id="box-success">
				<button type="button" onclick="javascript: closesuccess();" id="box-close">X</button>
				<center>
					<h2>SUCESSO!!</h2>
				</center>
				<center>'.$msgs.'</center>
			</div>';
}


function uniqueBox($title, $content) {
	echo '
	<script>
		function closeUBox(){
			document.getElementById("U-boxBody").style.display =  "none";
		}
	</script>
	
	<style>
	#U-boxBody {
		z-index: 1000;
		background-color: rgb(31 42 40 / 50%);
		position: fixed;
		margin: 0px;
		top: 0px;
		left: 0px;
		display: flex;
		justify-content: center;
		align-items: center;
		width: 100vw;
		height: 100vh;
	}

	#U-boxMain {
		position: relative;
		/* border: 1px solid; */
		min-height: 200px;
		max-height: 400px;
		min-width: 500px;
		max-width: 1000px;
		padding: 10px;
		background-color: #e3e3e3;
		box-shadow: 0px 0px 6px 0px black;
	}

		
	#U-boxButton {
		color: #2c3860;
		font-weight: bold;
		font-size: 20px;
		width: 30px;
		/* height: 30px; */
		background-color: unset;
		border: unset;
		position: absolute;
		right: 0px;
		top: 0px;
	}

	#U-boxButton:hover {
		background-color: #ffc8c8;
		border: 1px solid #440c0c;
		cursor: pointer;
	}

	#U-boxTitle {
		text-align: center;
	}

	
	.U-title {
		font-size: 15px;
		font-family: sans-serif;
		font-weight: bold;
	}

	#U-borderContent {
		min-height: 200px;
		max-height: 380px;
		min-width: 500px;
		max-width: 1000px;
		overflow: auto;
    	word-break: break-all;
	}
</style>
	
	';
	return '
	<div id="U-boxBody">
			<div id="U-boxMain">
				<button type="button" onclick="closeUBox()" id="U-boxButton">X</button>
				<div id="U-boxTitle">
					<label class="U-title">'.$title.'</label>
				</div>
				<div id="U-borderContent">
					<div id="U-boxContent">
						'.$content.'
					</div>
				</div>
			</div>
	</div>
	';
}



?>
