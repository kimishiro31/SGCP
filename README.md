<h1 align="center">🔗 SGCP
</h1>
<p align="center">Aplicação no qual permite o gerenciamento de uma clínica de podologia por meio de um ambiente web.</p>

### Desenvolvedores e Suporte
- +55 (11) 9 9448-9463 - Thiago


### Requisitos Recomendados

- S.O Ubuntu >14.04/Windows 7 ou posterior
- APACHE2 >2.0
- PHP >=5.0
- MYSQL >= 6.0
- PHPMYADMIN >=2.0


<h1 align="center">
    <a href="">:books: Tutorial de como configurar</a>
</h1>

### Ambiente utilizado:
  > - Ubuntu, versão 20.04.3 for Linux on x86_64 ((Ubuntu))</br>
  > - Apache, versão 2.4.41</br>
  > - PhP, versão 8.0.18</br>
  > - Mysql, versão 8.0.29</br>
  > - Phpmyadmin, versão 2.0</br>
Observação: o ambiente que estaremos será configurado desde a sua instalação.

### Preparação do Ubuntu:
  Vamos iniciar a preparação do ambiente Ubuntu, começando pela atualização e instalação das bibliotecas: 
  ``` barsh
  sudo apt update && sudo apt upgrade -y
  apt-get install sudo
  ```
  
  O próximo passo, será a configuração do Firewall do nosso servidor:
  > *O sistema pode perguntar se você realmente deseja fazer a ativação do Firewall, pode confirmar, pois já fizemos a liberação das portas necessárias*
  ``` barsh
  sudo ufw allow 80/tcp comment 'Apache'
  sudo ufw allow 443/tcp comment 'HTTPS'
  sudo ufw allow 22 comment 'ssh'
  sudo ufw enable
  ```
  
  Sistema operacional configurado!!

### Instalação Apache:
  Instalando o serviço apache:
  ```barsh
  sudo apt install apache2 -y
  ```
  
  Verifique se o serviço apache, foi instalado com sucesso:
  ```barsh
  sudo systemctl is-enabled apache2.service
  ```
  Ligue o serviço apache:
  > Caso o passo anterior tenha aparecido enabled, pule essa etapa.
  ```barsh
  sudo systemctl enable apache2.service
  ```
  Serviço apache, instalado com sucesso!!
  
### Instalação Mysql:

  Instalando o serviço do Mysql:
  ```barsh
  sudo apt install mysql-server -y
  ```
  
  Vamos entrar no ambiente Mysql e em seguida adicionaremos uma senha no usuário root do mysql:
  > Altere o campo **SUASENHA**, para uma senha de sua escolha:
  ```barsh
  sudo mysql
  ALTER USER 'root'@'localhost' IDENTIFIED WITH caching_sha2_password BY 'SUASENHA';
  exit
  ```

  Vamos utilizar de um script para fazermos algumas melhorias de segurança no serviço Mysql:
  > Após a execução do comando, ele fará as seguintes pergunta
  > 1) Enter password for user root:
  > R1) Digite a sua senha, que você colocou no root do mysql.
  > 2) Press y|Y for Yes, any other key for No: 
  > R2) Digite no, isso fará com que o padrão de senha permaneça do jeito default
  > 3) Change the password for root ?
  > R3) Digite não, pois acabamos de adicionar uma senha no root.
  > 4) Remove anonymous users? (Press y|Y for Yes, any other key for No) :
  > R4) Digite yes, ele removerá acesso do usuári anônimo
  > 5) Disallow root login remotely? (Press y|Y for Yes, any other key for No) :
  > R5) Digite yes, pois ele irá ajudar a manter o root mais seguro
  > 6) Remove test database and access to it? (Press y|Y for Yes, any other key for No) :
  > R6) Digite yes, pois ele removera a database de test
  > 7) Reload privilege tables now? (Press y|Y for Yes, any other key for No) : 
  > R7) Digite yes, pois ele ira dar um refresh.  
  ```barsh
  sudo mysql_secure_installation
  ```

  Serviço Mysql, instalado com sucesso!!

### Instalação PhP:

  Vamos fazer as atualizações das bibliotecas novamente, para podermos fazer a instalação do PhP:
  ```barsh
  sudo apt update && sudo apt upgrade -y
  ```
  
  Baixando o repositório do PhP:
  > Aperte a tecla [ENTER], caso solicite após a execução do comando abaixo
  ```barsh
  sudo apt install php libapache2-mod-php php-mysql
  ```
  
  Reinicie o sistema Apache, para que sincronize com o PhP instalado:
  ```barsh
  sudo systemctl restart apache2
  ```
  
  Serviço PhP, instalado com sucesso!!
  
  
### Instalação Phpmyadmin:

  Começando a instalação do Phpmyadmin:
  > Após a execução do comando abaixo, ele irá te perguntar:</br>
  > Do you want to continue? [Y/n]: 
  > R: responda com Y</br>
  > Após isso, ele vai te perguntar qual o serviço que você quer utilizar em conjunto com o phpmyadmin, vai aparecer marcado o Apache2, porem ele não estára realmente marcado, faça a seleção apertando SPACE, e após isso aperte o TAB para pular para o [Ok], e [ENTER] para confirmar a seleção.</br>
  > Após o carregamento de alguns componentes de instalação, irá aparecer outro aviso, apenas confirmer o YES, apertando a tecla [ENTER].</br>
  > Em seguida será solicitado a senha que você digitou la no passo do MYSQL para o usuário root.</br>
  ```barsh
  sudo apt install phpmyadmin
  ```
  
 Serviço Phpmyadmin, instalado com sucesso!! 
 
### Instalando a aplicação web:
  Vamos liberar a pasta onde será hospedado nosso serviço web, para a instalação do ambiente:
  ```barsh
  sudo chmod 777 /var/www/html
  ```
  Agora que tiramos as restrições da pasta, vamos fazer a cópia do nosso serviço:
  ```barsh
  cd /var/www/html
  git clone https://github.com/kimishiro31/SGCP
  ```
  
  Voltando a restrição da pasta onde nosso serviço web está localizado:
  ```barsh
    sudo chmod 770 /var/www/html
  ```
  
  Movendo o conteudo do repositório para a raiz:
  ```barsh
  mv /var/www/html/SGCP/* /var/www/html/
  ```
  
  Eliminando pastas desnecessárias:
  ```barsh
    cd /var/www/html
    rm .gitattributes
    rm -Rf .git
    rm -Rf .vscode
  ```
  
  Pronto serviço instalado com sucesso!!!
