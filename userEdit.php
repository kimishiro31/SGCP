<?php 
require_once 'engine/init.php';
doProtect();
getPageAccess($user_data['id'], 2);
$titlepage = "Controle de Usuário";
include 'layout/overall/header.php';

                    
    /*********************************************************
    **
    ** VALIDAÇÃO DO FORMULARIO DE EDIÇÃO DO USUARIO
    **
    **********************************************************/

	if (empty($_POST) === false && getToken('tokenEdit')) {
            $username = $_POST['login'];
            $password = $_POST['password'];
            $group = $_POST['group'];
            $status = $_POST['status'];
            $firstName = strtoupper(sanitizeString($_POST['first_name']));
            $lastName = strtoupper(sanitizeString($_POST['last_name']));
            $birthDate = $_POST['birthDate'];
            $gender = strtoupper($_POST['gender']);
            $rg = sanitizeString($_POST['rg']);
            $phoneP = str_replace(' ', '', (sanitizeString($_POST['phoneP'])));
            $phoneS = str_replace(' ', '', (sanitizeString($_POST['phoneS'])));
            $email = $_POST['email'];
            $ad_cep = sanitizeString($_POST['address_cep']);
            $ad_num = $_POST['address_num'];
            $ad_complemento = strtoupper(sanitizeString($_POST['address_comp']));
            $ad_rua = strtoupper(sanitizeString($_POST['address_rua']));
            $ad_bairro = strtoupper(sanitizeString($_POST['address_bairro']));
            $ad_cidade = strtoupper(sanitizeString($_POST['address_cidade']));
            $ad_estado = strtoupper(sanitizeString($_POST['address_estado']));
            $nationality = (empty($_POST['nationality'])) ? NULL : strtoupper(sanitizeString($_POST['nationality']));
            $profission = (empty($_POST['profission'])) ? NULL : strtoupper(sanitizeString($_POST['profission']));
            $photo = $_FILES['photo'];
            $user_id = $_POST['validator'];

            $required_fields = array('firstName', 'lastName', 'birthDate', 'gender',
            'cpf', 'rg', 'phoneP', 'ad_cep', 'ad_num', 'ad_rua', 'ad_bairro', 'ad_cidade', 'ad_estado');

            foreach($_POST as $key=>$value) {
                if (empty($value) && in_array($key, $required_fields) === true) {
                    $errors[] = "É obrigatório o preenchimento de todos os campos com o (*).";
                    break 1;
                }
            }

            /* VALIDAÇÕES DADOS DE LOGIN - LOGIN */
            if (preg_match("/^[a-zA-Z ]+$/", $username) === false) {
                $errors[] = "No campo login, somente é aceito caracteres alfabetico e sem acentuação.";
            }
            elseif (strlen($username) < $config['userNameLenght'][0] || strlen($username) > $config['userNameLenght'][1]) {
                $errors[] = "O login não está de acordo, é necessário um login entre ".$config['userNameLenght'][0]." e ".$config['userNameLenght'][1].".";
            }
            

            /* VALIDAÇÕES DADOS DE LOGIN - SENHA */
            if(!empty($password)) {
                if (preg_match("/^[a-zA-Z ]+$/", $password) === false) {
                    $errors[] = "No campo senha, somente é aceito caracteres alfabetico e sem acentuação.";
                }
                elseif (strlen($password) < $config['passwordLenght'][0] || strlen($password) > $config['passwordLenght'][1]) {
                    $errors[] = "A senha não está de acordo, é necessário um login entre ".$config['passwordLenght'][0]." e ".$config['passwordLenght'][1].".";
                }
            }
            /* VALIDAÇÕES DADOS DE LOGIN - GROUP */
            if (array_key_exists($group, $config['groups']) === false) {
                $errors[] = "Não existe esse nível de acesso.";
            }

            /* VALIDAÇÕES DADOS DE LOGIN - STATUS */
            if ($status != 0 && $status != 1) {
                $errors[] = "Não existe esse status.";
            }

            /* VALIDAÇÕES DADOS PESSOAIS - PRIMEIRO NOME */
            if (preg_match("/^[a-zA-Z ]+$/", $firstName) === false) {
                $errors[] = "No campo primeiro nome, somente é aceito caracteres alfabetico e sem acentuação.";
            } 
            elseif (strlen($firstName) > 20) {
                $errors[] = "O primeiro nome está muito longo.";
            }
            
            /* VALIDAÇÕES DADOS PESSOAIS - SEGUNDO NOME */
            if (preg_match("/^[a-zA-Z ]+$/", $lastName) === false) {
                $errors[] = "No campo sobrenome, somente é aceito caracteres alfabetico e sem acentuação.";
            } 
            elseif (strlen($lastName) > 80) {
                $errors[] = "O sobrenome está muito longo.";
            }

            /* VALIDAÇÕES DADOS PESSOAIS - DATA DE NASCIMENTO */
            
            if((int)date("d", strtotime($birthDate)) < 1 || (int)date("d", strtotime($birthDate)) > getDaysInMonth((int)date("m", strtotime($birthDate)), (int)date("Y", strtotime($birthDate)))) {
                $errors[] = "O dia escolhido é inexistente no calêndario.";
            }
            elseif ((int)date("m", strtotime($birthDate)) < 1 || (int)date("m", strtotime($birthDate)) > 12) {
                $errors[] = "Mês de nascimento escolhido não existe no calendario.";
            }

            if (is_numeric((int)date("Y", strtotime($birthDate))) === false) {
                $errors[] = "No ano do seu nascimento só é aceito numero de ".(date("Y")-100)."-".date("Y").".";
            }
            elseif ((int)date("Y", strtotime($birthDate)) < (date("Y")-100) || (int)date("Y", strtotime($birthDate)) > date("Y")) {
                $errors[] = "Mês de nascimento escolhido não existe no calendario.";
            }
            
            /* VALIDAÇÕES DADOS PESSOAIS - GÊNERO */
            
            if ($gender !== 'M' && $gender !== 'F') {
                $errors[] = "Favor verificar o gênero, o mesmo está errado.";
            }

            /* VALIDAÇÕES DADOS PESSOAIS - TELEFONE */
            if (is_numeric($phoneP) === false) {
                $errors[] = "numero de telefone só pode ter numeros.";
            }
            if (!empty($phoneS) && is_numeric($phoneS) === false) {
                $errors[] = "numero de telefone só pode ter numeros.";
            }

            /* VALIDAÇÕES ENDEREÇO - cep */
            if (is_numeric($ad_cep) === false) {
                $errors[] = "No cep somente é aceito numeração.";
            }
            elseif (strlen($ad_cep) < 1 || strlen($ad_cep) > 8) {
                $errors[] = "cep tem que conter no minímo de 8 caracteres.";
            }
            elseif (isSameCharacter($ad_cep)) {
                $errors[] = "cep invalido.";
            }

            if (isset($photo['name']) && $photo['error'] != 4) {
                $types = array('image/png', 'image/jpg', 'image/jpeg');

                list($width, $height) = getimagesize($photo['tmp_name']);

                // Valida se a imagem é tamanho valido
                if($photo['size'] > 0) {
                    // Valida se o tipo é valido
                    if(array_search($photo['type'], $types) !== false) {
                        if($width > config('picture')['width'] && $height > config('picture')['height']) {
                            $errors[] = "Essa imagem que você enviou ultrapassou o tamanho máximo de largura ".config('picture')['width']."px - altura ".config('picture')['width']."px.";
                        }
                    } else {
                        $errors[] = "O formato do arquivo enviado é invalido, você deve enviar um formato valido.";
                    }
                } else {
                    $errors[] = "O tamanho do arquivo está invalido.";
                }
            }
    }

    /*********************************************************
    **
    ** SE CASO O FORMULARIO DE EDIÇÃO VALIDAR
    ** CORRETAMENTE SEM ERROS
    **********************************************************/
    if (empty($_POST) === false && getToken('tokenEdit') && empty($errors)) {
            $register_data = array(
                /* CONTA */
                'username'		=>	$username,
                'password'	    =>	$password,
                'group'	        =>	$group,
                'status'	    =>	$status,
                'phoneP'		=>	$phoneP,
                'phoneS'		=>	$phoneS,
                'email'		=>	$email,
                'user_id'       => $user_id,
                /* USUARIO */
                'firstName'		=>	$firstName,
                'lastName'		=>	$lastName,
                'bithDate'		=>	$birthDate,
                'rg'		    =>	$rg,
                'gender'		=>	$gender,
                'ad_cep'		=>	$ad_cep,
                'ad_rua'		=>	$ad_rua,
                'ad_bairro'		=>	$ad_bairro,
                'ad_num'		=>	$ad_num,
                'ad_comp'		=>	$ad_complemento,
                'ad_cid'		=>	$ad_cidade,
                'ad_estado'		=>	$ad_estado,
                'nacionality'	=>	$nationality,
                'profission'	=>	$profission,
                'photo'         => $_FILES['photo']
            );
            echo output_msgs("As alterações de perfil foram efetuadas com sucesso!!<br/> Direcionando para a pagina de perfil.", "userViewer.php?data=".$user_id."");
            doUpdateUser($register_data);
    }

    
    /*********************************************************
    **
    ** SE CASO O FORMULARIO DE EDIÇÃO VALIDAR E ENCONTRAR ERRO
    **
    **********************************************************/
    if (empty($errors) === false) {
        header("HTTP/1.1 401 Not Found");
        echo output_errors($errors);
    }


    /*********************************************************
    **
    ** VALIDAÇÃO SE O USUARIO QUE ESTÁ SENDO EDITADO EXISTE
    ** SE EXISTIR ELE DA O FORMULARIO DE EDIÇÃO
    **********************************************************/
    if(isUserExist($_POST['validator']) !== false) {
        $user_ID = isUserExist($_POST['validator']);
        $account_ID = getAccountID($user_ID);
    ?>
            <!-- JAVA SCRIPT -->
            <script> 
                jQuery(function($) { 
                    $("#phoneP").mask("(99) 9999-99990"); 
                    $("#phoneS").mask("(99) 9999-99990"); 
                    $("#day_bithdate").mask("90"); 
                    $("#month_bithdate").mask("90"); 
                    $("#year_bithdate").mask("9990"); 
                    $("#cpf").mask("999.999.999/90"); 
                    $("#address_cep").mask("99999-990"); 
                }); 

                function readURL(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                        $(input).next()
                        .attr('src', e.target.result)
                    };
                    reader.readAsDataURL(input.files[0]);
                    }
                    else {
                        var img = input.value;
                        $(input).next().attr('src',img);
                    }
                }

                function verificaMostraBotao(){
                    $('input[type=file]').each(function(index){
                        if ($('input[type=file]').eq(index).val() != ""){
                            readURL(this);
                            $('.hide').show();
                        }
                    });
                }

                $('input[type=file]').on("change", function(){
                verificaMostraBotao();
                });

            </script>


            <form action="userEdit.php" method="POST" enctype="multipart/form-data">


                <fieldset>
                    <legend style="text-align: center;">Dados de Login</legend>
                    <table border="0" class="tableRegisterUsers">
                        <tr>
                            <td>
                                Login:
                                <input type="text" class="mediumInputTxt" name="login" value="<?php echo getAccountLogin($account_ID) ?>"/>
                            </td>
                            <td>
                                Senha:
                                <input type="password" class="mediumInputTxt" name="password"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Permissão:
                                <select class="mediumSelectInp" name="group">
                                    <?php 
                                        for($d = 0; $d < count($config['groups']);++$d) { 
                                            if($d == getAccountGroup($account_ID)) 
                                                echo '<option value='.getAccountGroup($account_ID).' selected>'.$config['groups'][getAccountGroup($account_ID)].'</option>';
                                            else
                                                echo '<option value="'.$d.'">'.$config['groups'][$d].'</option>';
                                        }
                                    ?>
                                </select>
                            </td>
                            <td>
                                Status:
                                <select class="smallSelectInp" name="status">
                                    <?php 
                                        if(getAccountStatus($account_ID) == 1) {
                                            echo '<option value="0">DESATIVADO</option>';
                                            echo '<option value="1" selected>ATIVADO</option>';
                                        }
                                        else {
                                            echo '<option value="0" selected>DESATIVADO</option>';
                                            echo '<option value="1">ATIVADO</option>';
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </fieldset>

                <fieldset>
                    <legend style="text-align: center;">Dados Pessoais</legend>
                    <table border="0" class="tableRegisterUsers">
                        <tr>
                            <td>
                                Primeiro Nome<font color="red">*</font>:
                                <input type="text" class="mediumInputTxt" name="first_name" value="<?php echo getUserFirstName($user_ID) ?>" required/>
                            </td>
                            <td>
                                Sobrenome<font color="red">*</font>:
                                <input type="text" class="mediumInputTxt" name="last_name" value="<?php echo getUserLastName($user_ID) ?>" required/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Data de Nascimento<font color="red">*</font>:
                                <input type="date" name="birthDate" id="birthDate" class="dateInput" value="<?php echo implode('-', array_reverse(explode('/', getUserBirthDate($user_ID)))); ?>">
                            </td>
                            <td>
                                Gênero<font color="red">*</font>:
                                <select type="text" class="smallSelectInp" name="gender" required>
                                    <?php
                                        if(getUserGender($user_ID) === 'M') {
                                            echo '<option value="m" selected>Masculino</option>';
                                            echo '<option value="f">Feminino</option>';
                                        }
                                        else {
                                            echo '<option value="m">Masculino</option>';
                                            echo '<option value="f" selected>Feminino</option>';
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                CPF<font color="red">*</font>:
                                <input type="text" class="mediumInputTxt" id="cpf" value="<?php echo getUserCPF($user_ID) ?>" disabled/>
                            </td>
                            <td>
                                RG<font color="red">*</font>:
                                <input type="text" class="mediumInputTxt" name="rg" value="<?php echo getUserRG($user_ID) ?>" required/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Telefone<font color="red">*</font>:
                                <input type="text" class="mediumInputTxt" id="phoneP" name="phoneP" value="<?php echo getAccountPPhone($account_ID) ?>" required/>
                            </td>
                            <td>
                                Telefone Segundario<font color="red"></font>:
                                <input type="text" class="mediumInputTxt" id="phoneS" name="phoneS" value="<?php echo getAccountSPhone($account_ID) ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Email:
                                <input type="text" class="mediumInputTxt" name="email" value="<?php echo getAccountEmail($account_ID) ?>"/>
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
                                <input type="text" id="address_cep" class="mediumInputTxt" name="address_cep" value="<?php echo getUserAddressCEP($user_ID) ?>" required/>
                            </td>
                            <td>
                                Rua<font color="red">*</font>:
                                <input type="text" id="address_logradouro" class="mediumInputTxt" name="address_rua" value="<?php echo getUserAddressStreet($user_ID) ?>" hidden/>
                                <input type="text" id="address_logradouro_disabled" class="mediumInputTxt" value="<?php echo getUserAddressStreet($user_ID) ?>" disabled/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Numero<font color="red">*</font>:
                                <input type="text" id="address_numero" class="smallInputTxt" name="address_num" value="<?php echo getUserAddressNumber($user_ID) ?>" required/>
                            </td>
                            <td>
                                Complemento<font color="red"></font>:
                                <input type="text" class="smallInputTxt" name="address_comp" value="<?php echo getUserAddressComplement($user_ID) ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Bairro<font color="red">*</font>:
                                <input type="text" id="address_bairro" class="smallInputTxt" name="address_bairro" value="<?php echo getUserAddressDistrict($user_ID) ?>" hidden/>
                                <input type="text" id="address_bairro_disabled" class="mediumInputTxt" value="<?php echo getUserAddressDistrict($user_ID) ?>" disabled/>
                            </td>
                            <td>
                                Cidade<font color="red">*</font>:
                                <input type="text" id="address_cidade" class="smallInputTxt" name="address_cidade" value="<?php echo getUserAddressCity($user_ID) ?>" hidden/>
                                <input type="text" id="address_cidade_disabled" class="mediumInputTxt" value="<?php echo getUserAddressCity($user_ID) ?>" disabled/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Estado<font color="red">*</font>:
                                <input type="text" id="address_uf" class="mediumInputTxt" name="address_estado" value="<?php echo getUserAddressUF($user_ID) ?>" hidden/>
                                <input type="text" id="address_uf_disabled" class="mediumInputTxt" value="<?php echo getUserAddressUF($user_ID) ?>" disabled/>
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
                                <input type="text" class="mediumInputTxt" name="profission" value="<?php echo getUserProfission($user_ID) ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="ue-imgFrame">
                                    <img id="pt-moldure" src="<?php echo getUserFolderForIMG(getUserCPF($user_ID)).'/'.getUserPhotoName($user_ID); ?>"></img>
                                </div>
                                Foto<font color="red"></font>:
                                <input type="file" name="photo" class="hide" id="file" onchange="PreviewImage();" accept="image/png;image/jpg;image/jpeg">
                            </td>
                        </tr>
                    </table>
                </fieldset>
                <br/>
                <br/>
                <?php setToken('tokenEdit') ?>
                <input name="token" type="text" value="<?php echo addToken('tokenEdit') ?>" hidden/>
                <input name="validator" type="text" value="<?php echo $user_ID ?>" hidden/>
                <a href="userViewer.php?data=<?php echo $_POST['validator']; ?>">
                    <button type="button" class="otherButton">VOLTAR</button>
                </a>
                <button type="submit" class="enterButton">SALVAR</button>
            </form>



            <!-- JAVA SCRIPT -->
            <script type="text/javascript">
                function PreviewImage() {
                    var oFReader = new FileReader();
                    oFReader.readAsDataURL(document.getElementById("file").files[0]);

                    oFReader.onload = function (oFREvent) {
                        document.getElementById("pt-moldure").src = oFREvent.target.result;
                    };
                };
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
    }

    include 'layout/overall/footer.php';
?>