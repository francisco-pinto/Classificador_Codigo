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
                //Permite a seleção de duas datas (Início e fim do projeto)
                $( "#datepicker" ).datepicker({ dateFormat: 'dd/mm/yy' });
                $( "#datepicker2" ).datepicker({ dateFormat: 'dd/mm/yy' });
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

            <input type="submit" value="Submeter" name="submit">
        </form>


   </body>
</html>


<?php
    if(isset($_POST['submit'])){
        if(!empty($_POST['languageID'])){
            if(!empty($_POST['name'])){
                if(!empty($_POST['input']) && !empty($_POST['output'])){

                    $inicio_date = $_POST['Begin_Date'];
                    $fim_date = $_POST['End_Date'];

                    $inicio_date = str_replace('/', '-', $inicio_date);
                    $fim_date = str_replace('/', '-', $fim_date);

                    /*echo "$inicio_date <br>";
                    echo "$fim_date <br>";*/

                    $Data_Inicio = date('Y-m-d H:i:s', strtotime($inicio_date)); 
                    $Data_Fim = date('Y-m-d H:i:s', strtotime($fim_date));

                    /*echo "Data de Início: $Data_Inicio <br>";
                    echo "Data de Fim: $Data_Fim <br><br><br>";*/


                    if(!empty($_POST['Begin_Date']) && $Data_Inicio > date("Y-m-d H:i:s")){
                        if(!empty($_POST['End_Date']) && $Data_Fim > $Data_Inicio){
                        
                            echo "Início da colocação do projeto";

                            
                            $user_id =  $_SESSION['user_Id'];  
                            $languageID = $_POST['languageID'];
                            $name= $_POST['name'];
                            $input = $_POST['input'];
                            $output = $_POST['output'];


                            /* Inserir os valores de casos de teste */
                            $sql_Casos_Teste = "INSERT INTO Casos_Teste (Input, Output) VALUES ('$input', '$output');";
                            $CasosTeste = $db->query($sql_Casos_Teste);
                           
                            /*Ir buscar o último ID que foi inserido
                            ,ou seja, o ID do casos de teste*/
                            $stmt = $db->query("SELECT LAST_INSERT_ID()");
                            $CasosTesteID = $stmt->fetchColumn();

                            /*$SQL_CasosTesteID = "SELECT Id FROM Casos_Teste WHERE Input = '$input' and Output = '$output' AND Data_CasosTeste = '$todayDate'";                          
                            $CasosTesteID = $db->query($SQL_CasosTesteID);
                            $CasosTesteID = $CasosTesteID->fetch(PDO::FETCH_ASSOC);*/
                            //Responsável pelo retorno do valor

                            /*echo "<br> Casos de Teste ID: $CasosTesteID <br>";
                            echo "Begin Date: $Data_Inicio <br>";
                            echo "End Date: $Data_Fim <br>";
                            echo "Language ID: $languageID <br>";   */                         

                            /*$Begin_Date = str_replace('/', '-', $Begin_Date);
                            $End_Date = str_replace('/', '-', $End_Date);

                            $Data_Inicio = strtotime($Begin_Date);
                            $Data_Fim = strtotime($End_Date);*/
                            /*$Begin_DateTimestamp = date('Y-m-d H:i:s', strtotime($Begin_Date));  
                            $End_DateTimestamp = date('Y-m-d H:i:s', strtotime($End_Date));  */

                            $sql = "INSERT INTO Projeto (Nome, Data_Projeto, Data_Limite, LinguagemID, CasosTesteID) VALUES ('$name', '$Data_Inicio', '$Data_Fim', '$languageID', '$CasosTesteID');";
        
                            // use exec() because no results are returned
                            $db->exec($sql);
                            echo "<br><br><br>Base de Dados atualizada";
                            die();
                        
                        }else{
                        echo "Data final terá de ser superior à data inicial";
                        }
                    }else{
                        echo "Data Inválida. Dia terá de ser superiro ao de hoje";
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