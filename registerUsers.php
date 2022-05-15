<?php 
require_once 'engine/init.php';
doProtect();
getPageAccess($user_data['id'], 2);
$titlepage = "Controle de Usuário";
include 'layout/overall/header.php'; 
?>
<!-- JAVA SCRIPT -->
<script> 
    $(document).ready(function() {
        /// Quando usuário clicar em salvar será feito todos os passo abaixo
        $('#submit').click(function() {
            var dados = $('#formRegister').serialize();

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'ajaxRequisit.php?subpage=register',
                async: true,
                data: dados,
                success: function(response) {
					$("#result").html(response);
                }
            });

            return false;
        });
    });
	
	jQuery(function($){ 
		$("#phoneP").mask("(99) 9999-99990"); 
		$("#phoneS").mask("(99) 9999-99990"); 
		$("#day_bithdate").mask("90"); 
		$("#month_bithdate").mask("90"); 
		$("#year_bithdate").mask("9990"); 
		$("#cpf").mask("999.999.999/90"); 
		$("#address_cep").mask("99999-990"); 
    }); 
</script>

<div id="result"></div>

<form id="formRegister" enctype="multipart/form-data">
    <fieldset>
    <legend style="text-align: center;">Dados Pessoais</legend>
        <table border="0" class="tableRegisterUsers">
            <tr>
                <td>
                    Primeiro nome<font color="red">*</font>:
                    <input type="text" class="mediumInputTxt" name="first_name" required/>
                </td>
                <td>
                    Sobrenome<font color="red">*</font>:
                    <input type="text" class="mediumInputTxt" name="last_name" required/>
                </td>
            </tr>
            <tr>
                <td>
                    Data de Nascimento<font color="red">*</font>:
                    <input type="date" name="birthDate" id="birthDate" class="dateInput">
                </td>
                <td>
                    Gênero<font color="red">*</font>:
                    <select type="text" class="smallSelectInp" name="gender" required>
                        <option value="m">Masculino</option>
                        <option value="f">Feminino</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    CPF<font color="red">*</font>:
                    <input type="text" class="mediumInputTxt" id="cpf" name="cpf" required/>
                </td>
                <td>
                    RG<font color="red">*</font>:
                    <input type="text" class="mediumInputTxt" name="rg" required/>
                </td>
            </tr>
            <tr>
                <td>
                    Telefone<font color="red">*</font>:
                    <input type="text" class="mediumInputTxt" id="phoneP" name="phoneP" required/>/
                </td>
                <td>
                    Telefone Segundario<font color="red"></font>:
                    <input type="text" class="mediumInputTxt" id="phoneS" name="phoneS"/>/
                </td>
            </tr>
            <tr>
                <td>
                    Email:
                    <input type="text" class="mediumInputTxt" name="email"/>
                </td>
            </tr>
        </table>
    </fieldset>
<br/>
<br/>
    <fieldset>
        <legend style="text-align: center;">Endereço</legend>
        <table border="0" class="tableRegisterUsers">
            <tr>
                <td>
                    CEP<font color="red">*</font>:
                    <input type="text" id="address_cep" class="mediumInputTxt" name="address_cep" required/>
                </td>
                <td>
                    Rua<font color="red">*</font>:
                    <input type="text" id="address_logradouro" class="mediumInputTxt" name="address_rua" hidden/>
                    <input type="text" id="address_logradouro_disabled" class="mediumInputTxt" disabled/>
                </td>
            </tr>
            <tr>
                <td>
                    Numero<font color="red">*</font>:
                    <input type="text" id="address_numero" class="smallInputTxt" name="address_num" required/>
                </td>
                <td>
                    Complemento<font color="red"></font>:
                    <input type="text" class="smallInputTxt" name="address_comp"/>
                </td>
            </tr>
            <tr>
                <td>
                    Bairro<font color="red">*</font>:
                    <input type="text" id="address_bairro" class="smallInputTxt" name="address_bairro" hidden/>
                    <input type="text" id="address_bairro_disabled" class="mediumInputTxt" disabled/>
                </td>
                <td>
                    Cidade<font color="red">*</font>:
                    <input type="text" id="address_cidade" class="smallInputTxt" name="address_cidade" hidden/>
                    <input type="text" id="address_cidade_disabled" class="mediumInputTxt" disabled/>
                </td>
            </tr>
            <tr>
                <td>
                    Estado<font color="red">*</font>:
                    <input type="text" id="address_uf" class="mediumInputTxt" name="address_estado" hidden/>
                    <input type="text" id="address_uf_disabled" class="mediumInputTxt" disabled/>
                </td>
            </tr>
        </table>
    </fieldset>
<br/>
<br/>
    <fieldset>
        <legend style="text-align: center;">Outros</legend>
        <table border="0" class="tableRegisterUsers">
            <tr>
                <td>
                    Nacionalidade<font color="red"></font>:
                    <select class="mediumInputTxt" name="nationality">
                        <option value="brasileiro" selected>brasileiro</option>
                        <option value="canadense">canadense</option>
                        <option value="chinês">chinês</option>
                        <option value="dinamarquês">dinamarquês</option>
                        <option value="francês">francês</option>
                        <option value="alemão">alemão</option>
                        <option value="italiano">italiano</option>
                        <option value="japonês">japonês</option>
                        <option value="mexicano">mexicano</option>
                        <option value="português">português</option>
                        <option value="russo">russo</option>
                        <option value="russo">árabe</option>
                        <option value="espanhol">espanhol</option>
                        <option value="americano">americano</option>
                    </select>
                </td>
                <td>
                    Profissão<font color="red"></font>:
                    <input type="text" class="mediumInputTxt" name="profission"/>
                </td>
            </tr>

        </table>
    </fieldset>
<br/>
<br/>
                <a href="myaccount">
                    <button type="button" class="otherButton">VOLTAR</button>
                </a>
	<?php setToken('registerUser') ?>
	<input name="token" type="text" value="<?php echo addToken('registerUser') ?>" hidden/>
	<button type="submit" id="submit" class="enterButton">CADASTRAR</button>
</form>

<!-- JAVA SCRIPT -->
<script type="text/javascript">
	$("#address_cep").focusout(function(){
		$.ajax({
			url: 'https://viacep.com.br/ws/'+$(this).val()+'/json/',
			dataType: 'json',
			success: function(resposta){
				$("#address_logradouro").val(resposta.logradouro);
				$("#address_complemento").val(resposta.complemento);
				$("#address_bairro").val(resposta.bairro);
				$("#address_cidade").val(resposta.localidade);
				$("#address_uf").val(resposta.uf);

				$("#address_logradouro_disabled").val(resposta.logradouro);
				$("#address_complemento_disabled").val(resposta.complemento);
				$("#address_bairro_disabled").val(resposta.bairro);
				$("#address_cidade_disabled").val(resposta.localidade);
				$("#address_uf_disabled").val(resposta.uf);
				$("#address_numero").focus();
			}
		});
	});
</script>








<?php
include 'layout/overall/footer.php';
?>