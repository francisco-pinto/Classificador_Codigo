<?php
   ob_start();
   session_start();

   //Verificação de professor ou aluno

    if (isset($_SESSION['user_Username']) && isset($_SESSION['user_Name'])) 
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
      <title>Index</title>
      
      <style>
         body {
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #ADABAB;
         }
         
         .form-signin {
            max-width: 330px;
            padding: 15px;
            margin: 0 auto;
            color: #017572;
         }
         
         .form-signin .form-signin-heading,
         .form-signin .checkbox {
            margin-bottom: 10px;
         }
         
         .form-signin .checkbox {
            font-weight: normal;
         }
         
         .form-signin .form-control {
            position: relative;
            height: auto;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            padding: 10px;
            font-size: 16px;
         }
         
         .form-signin .form-control:focus {
            z-index: 2;
         }
         
         .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
            border-color:#017572;
         }
         
         .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-color:#017572;
         }
         
         h2{
            text-align: center;
            color: #017572;
         }
      </style>

        <!--De forma a conseguirmos obter um calendário-->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script>
            $( function() {
                $( "#datepicker" ).datepicker();
            } );
        </script>
   </head>
	
   <body>
      
        <h2>Criação de Projeto</h2>  
        
        <h3>Insira os critérios de avaliação</h3>
        

        <!-- Quantos input e output iremos ter? Fazer dinamicamente casos de teste -->
        <form action="" method="post" enctype="multipart/form-data">
            <label for="name">Nome do Projeto:</label>
            <input type="text" id="text" name="name"><br><br><br><br>
            <label for="input">Input:</label>
            <input type="text" id="input" name="input"><br><br>
            <label for="output">Output:</label>
            <input type="text" id="output" name="output"><br><br>
            <br>
            <br>
            <br>
            <br>  
    
        <!-- Escolha da linguagem de programação -->
            <label for="Linguagens">Escolher a linguagem:</label><br>
            <?php
                $sql = "SELECT * FROM Linguagem";

                $q = $db->prepare($sql);
                $q->execute();
                $q->setFetchMode(PDO::FETCH_ASSOC);

                while ($linguagens = $q->fetch()) {
                    echo '<input type="radio" id="' . $linguagens['Id'] . '" name="' . $linguagens['Id'] . '" value="' . $linguagens['Linguagem'] . '">';
                    echo '<label for="' . $linguagens['Linguagem'] . '">' . $linguagens['Linguagem'] . '</label><br>';
                }
            ?>
            <br>
            <br>
            <br>
            <br>     


            <!-- Data de início e data de fim do projeto -->
            <p>Data de início de Projeto: <input type="text" id="datepicker" name="Begin_Date"></p>
            <br><br>
            <p>Data de Fim de Projeto: <input type="text" id="datepicker" name="End_Date"></p>

            <input type="submit" value="Submeter" name="submit">
        </form>


   </body>
</html>


<?php
    if(isset($_POST['submit'])){
        if(!empty($_POST['language'])){
            if(!empty($_POST['name'])){
                if(!empty($_POST['input']) && !empty($_POST['output'])){
                    if(!empty($_POST['Begin_Date']) || $_POST['Begin_Date'] > date("Y/m/d")){

                        echo "Início da colocação do projeto";

                        
                        $user_id =  $_SESSION['user_Id'];  
                        $languageID = $_POST['language'];
                        $name= $_POST['name'];
                        $input = $_POST['input'];
                        $output = $_POST['output'];
                        $Begin_Date = $_POST['Begin_Date'];
                        $End_Date = $_POST['End_Date'];




                        $sql_Casos_Teste = "INSERT INTO Casos_Teste (Input, Output) VALUES ('$input', '$output');";
                    
                        $SQL_CasosTesteID = "SELECT ID FROM Casos_Teste WHERE Input = '$input' and Output = '$output'";
                        $CasosTesteID = $db->exec($SQL_CasosTesteID);

                        $sql = "INSERT INTO Projeto (Nome, Data_Projeto, Data_Limite, Linguagem, Data_Upload, CasosTesteID) VALUES ('$name', '$Begin_Date', '$End_Date', '$languageID', '$todayDate', '$CasosTesteID');";
    
                        // use exec() because no results are returned
                        $db->exec($sql_Casos_Teste);
                        $db->exec($sql);
                        echo "<br><br><br>Base de Dados atualizada";
                        die();
                        

                        echo "Base de Dados atualizada";
                    }else{
                        echo "Data Inválida";
                    }
                }else{
                    echo "Complete os casos de teste";
                }
            }else{
                echo "Insira o nome do projeto";
            }
        }else{
            echo "Preencha os dados todos!";
        }
    }

?>