<?php
   ob_start();
   session_start();

   //Verificação de professor ou aluno

    if (isset($_SESSION['user_Username']) && isset($_SESSION['user_Name']) && $_SESSION['usertype_Id'] == 2) 
    {
        echo "Welcome to the member's area, " . $_SESSION['user_Username'] . "!";
    }else 
    {
        header("Location: login.php");
        die(); //pega
    }


    /*Ligação da Base de Dados*/
    $servername = "localhost";
    $username = "root";         //Default credencials wamp
    $password = "";

    try {
        $db = new PDO("mysql:host=$servername;dbname=Classificador_Codigo", $username, $password);
        // set the PDO error mode to exception
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully";
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
?>

         
<html lang = "pt">
   
   <head>
      <title>Editar do projeto</title>
      <link rel="stylesheet" href="css/style.css">      


        <!--De forma a conseguirmos obter um calendário-->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
   </head>
	
   <body>
      
        <h2>Editar Projetos</h2>  
        
        <div class="EscolhaProjeto">
            <form action="" method="post" enctype="multipart/form-data">
                <label for="Projeto">Escolha o projeto:</label><br>
                <?php
                    $sql = "SELECT * FROM Projeto";

                    $q = $db->prepare($sql);
                    $q->execute();
                    $q->setFetchMode(PDO::FETCH_ASSOC);

                    while ($projeto = $q->fetch()) {
                        $linguagem_id = $projeto["LinguagemID"];
                        echo '<input type="radio" id="' . $projeto['Id'] . '" name="projeto" value="' . $projeto['Id'] . '">';
                        echo '<label for="' . $projeto['Id'] . '">' . $projeto['Nome'] . '</label><br>'; 
                    }
                ?>

                <input type="submit" value="Submeter" name="submit_Projeto" onclick="clear_div()">
            </form>
        </div>
       
        <?php
            if(isset($_POST['submit_Projeto'])){
                if(!empty($_POST['projeto'])) {
                    
                    $projetoID = $_POST['projeto'];
                    echo $projetoID;

                    //Projeto 
                    $ProjetoNome = $db->query("SELECT Nome FROM Projeto WHERE ID = '$projetoID'")->fetch();
                    echo $ProjetoNome;

                    //Casos Teste
                        //Num casos
                    //Funcoes não permitidas
                        //Num Funcoes
                }
            }
        ?>



        <div hidden class="InformacoesProjeto">
        <h3>Insira os critérios de avaliação</h3>
                
                <!-- Quantos input e output iremos ter? Fazer dinamicamente casos de teste -->
                <form action="" method="post" enctype="multipart/form-data">
                    <label for="name">Nome do Projeto:</label>
                    <input type="text" id="text" name="name"><br><br><br><br>
        
                    <!-- Número de casos de teste que pretende -->
                    <div class="NumCasosTeste">
                        <label for="CasosTeste">Números de Casos Teste (Min: 1 | Máx: 8) : </label>
                        <input type="text" id="textCasosTeste" name="CasosTesteNum"><br><br>
                        <input type="button" value="Inserir" id="textCasosTeste_Button" name="submit_Casos" onclick="addCasosTeste()">
                    </div>
        
                    <!-- Casos Teste -->
                    <div id="DisplayCasosTeste" class="CasosTeste" >
                        
                        <h3>Inputs</h3>
        
                        <div id="InputDiv" >
                        
                        </div>
        
                        <br>
                        <h3>Outputs</h3>
        
                        <div id="OutputDiv" >
                        
                        </div>
                    </div>

                    <br><br>
            
                    <!-- Escolha da linguagem de programação -->
                    <label for="Linguagens">Escolher a linguagem:</label><br>
                    <?php
                        $sql = "SELECT * FROM Linguagem";
        
                        $q = $db->prepare($sql);
                        $q->execute();
                        $q->setFetchMode(PDO::FETCH_ASSOC);
        
                        while ($linguagens = $q->fetch()) {
                            echo '<input type="radio" id="' . $linguagens['Id'] . '" name="languageID" value="' . $linguagens['Id'] . '">';
                            echo '<label for="' . $linguagens['Id'] . '">' . $linguagens['Linguagem'] . '</label><br>';
                        }
                    ?>
                    <br>
                    <br>
                    <br>
                    <br>     
        
        
                    <!-- Data de início e data de fim do projeto -->
                    <p>Data de início de Projeto: <input type="text" id="datepicker" name="Begin_Date"></p>
                    <br><br>
                    <p>Data de Fim de Projeto: <input type="text" id="datepicker2" name="End_Date"></p>
        
                    <br>
                    <br>    
        
                    <!-- Funções que são proibídas no projeto -->
                    <div class="FuncoesProibidasClass">
                        <label>Nomes das funções proibidas a utilizar: </label>
                        <input type="text" id="TextFuncoesProibidas" name="FuncoesProibidas"><br><br>
                        <input type="button" value="Inserir" id="FuncoesProibidas_Button" name="submit_FuncoesProibidas" onclick="addFuncoesProibidas()">
                    </div>
        
                    <!-- Funções proibídas -->
                    <div id="DisplayFuncoesProibidas" class="CasosTeste" >
                        <h3>Funções proibidas</h3>
        
                        <div id="FuncoesProibidasInput" >
                        
                        </div>
                    </div>
        
                    <br>
                    <br>    
        
                    <input type="submit" value="Submeter" name="submit_Total">
                </form>
        </div>
        
   </body>
</html>

<script>
    function clear_div() {
        document.getElementById("EscolhaProjeto").innerHTML = "";
    }
</script>
