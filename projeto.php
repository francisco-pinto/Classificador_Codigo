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
      <title>Criação do projeto</title>
      <link rel="stylesheet" href="css/style.css">      


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

            <!-- Número de casos de teste que pretende -->
            <div class="NumCasosTeste">
                <label for="CasosTeste">Números de Casos Teste (Min: 1 | Máx: 8) : </label>
                <input type="text" id="textCasosTeste" name="CasosTesteNum"><br><br>
                <input type="button" value="Submeter" id="textCasosTeste_Button" name="submit_Casos" onclick="addCasosTeste()">
            </div>

            <!-- Casos Teste -->
            <div id="DisplayCasosTeste" class = "CasosTeste" >
                <?php
                    /*Para casos Teste*/
                    //$numCasos = 0;

                    /*Atribuição dos Casos teste*/
                    /* if(isset($_POST['submit_Casos'])){
                        $numCasos = $_POST['CasosTesteNum'];

                        if($numCasos > 0 && $numCasos < 8){
                            for ($i = 1; $i <= $numCasos; $i++) {
                                echo '<label for="input">Input:</label>
                                <input type="text" id="input' . $i . ' " name="input"><br><br>
                                <label for="output">Output:</label>
                                <input type="text" id="output' . $i . ' " name="output"><br><br>';
                            }
                        }else{
                            echo "Insira um valor correto.";
                        }
                    }*/
                ?>
            </div>

      <!--      <label for="input">Input:</label>
            <input type="text" id="input" name="input"><br><br>
            <label for="output">Output:</label>
            <input type="text" id="output" name="output"><br><br>-->


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

            <input type="submit" value="Submeter" name="submit_Total">
        </form>


   </body>
</html>


<script>
    function addCasosTeste() {

        var numCasos = document.getElementById('textCasosTeste').value;
        var foo = document.getElementById("DisplayCasosTeste");

        var inputText = "Input<br>";
        var outputText = "<br>Output";
       
        for(var i = 0; i < numCasos; i++){
            //Create an input type dynamically.   
            var elementInput = document.createElement("input");
            //Assign different attributes to the element. 
            elementInput.type = text;
            elementInput.value = " ";
            elementInput.name = "input"; 

            foo.appendChild(elementInput);
        }

        for(var i = 0; i < numCasos; i++){
            //Create an input type dynamically.   
            var elementOutput = document.createElement("input");
            //Assign different attributes to the element. 
            elementOutput.type = text;
            elementOutput.value = " ";
            elementOutput.name = "output"; 

            foo.appendChild(elementOutput);
        }

        /*var elementOutput = document.createElement("input");
        //Assign different attributes to the element. 
        elementOutput.type = text;
        elementOutput.value = " ";
        elementOutput.name = "output"; */


        //Append the element in page (in span).  
        //foo.appendChild(elementOutput);

    }
</script>



<?php

    /*Submissão na base de dados*/
    if(isset($_POST['submit_Total'])){
        if(!empty($_POST['languageID'])){
            if(!empty($_POST['name'])){
                if(!empty($_POST['input']) && !empty($_POST['output'])){      //Tem pelo menos um input

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


                            /*Criação do Projeto*/
                            $sql = "INSERT INTO Projeto (Nome, Data_Projeto, Data_Limite, LinguagemID) VALUES ('$name', '$Data_Inicio', '$Data_Fim', '$languageID');";
        

                            /*Ir buscar o último ID que foi inserido
                            ,ou seja, o ID do último projeto*/
                            $stmt = $db->query("SELECT LAST_INSERT_ID()");
                            $ProjetoID = $stmt->fetchColumn();

                            /* Inserir os valores de casos de teste */
                            for ($i = 1; $i <= $numCasos; $i++) {
                                $input = $_POST['input' . $i . ''];
                                $output = $_POST['output' . $i . ''];
                                
                                $sql_Casos_Teste = "INSERT INTO Casos_Teste (Input, Output, ProjetoID) VALUES ('$input', '$output', '$ProjetoID');";
                                $CasosTeste = $db->query($sql_Casos_Teste);
                            }


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