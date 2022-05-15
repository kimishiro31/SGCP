	<script>
		// onkeydown="EnterKeyFilter();"
		// Desativa a Tecla Enter
		function getKey(key)
		{  
		  if (window.event.keyCode == key)
		  {   
//			document.querySelector(".ocultFirst").click(); // Se precisar que ele aperte algum botão
			event.preventDefault();
			event.returnValue=false;
			event.cancel = true;
		  }
		}
	</script>