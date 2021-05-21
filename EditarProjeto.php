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
        
        <div id="EscolhaProjeto">
            <form action="/Projeto/ConfiguracaoProjeto.php" method="post" enctype="multipart/form-data">
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

                <input type="submit" value="Submeter" name="submit_Projeto">
            </form>
        </div>
   </body>
</html>

