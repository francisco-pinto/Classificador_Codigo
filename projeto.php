<?php
   ob_start();
   session_start();

   //Verificação de professor ou aluno

    if (!isset($_SESSION['user_Username']) && isset($_SESSION['user_Name']) && $_SESSION['usertype_Id'] == 2)
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
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
?>

         
<html lang = "pt">
   
   <head>
      <title>Create Project</title>
      <link rel="stylesheet" href="css/style.css">      


        <!--De forma a conseguirmos obter um calendário-->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.14.30/js/bootstrap-datetimepicker.min.js"></script>

        <script>
            $( function() {
                //Permite a seleção de duas datas (Início e fim do projeto)
                $( "#datepicker" ).datepicker({ dateFormat: 'dd/mm/yy' });
                $( "#datepicker2" ).datepicker({ dateFormat: 'dd/mm/yy' });
            } );
        </script>
   </head>
	
   <body>
    <div class="topnav">
        <a class="logo" href="index.php"><img src="css\Images\logoutad.png" alt = "logoutad"></a>

        <a class="logout funcionalidade" ><form method="post">
        <button class = "button" type = "logout" name = "button_logout">Logout</button>
        </form></a>

        <a class="funcionalidade" href="EditarProjeto.php">Edit Project</a>
    
        <a class="active funcionalidade"  href="index.php">Home</a>
    </div>


    <div class="container">
        <h2>Create Project</h2>  

        <!-- Quantos input e output iremos ter? Fazer dinamicamente casos de teste -->
        <form action="" method="post" enctype="multipart/form-data">
            <label for="name">Project name:</label>
            <input class = "inputfile" type="text" name="name" id="text"><br><br><br>
            <!-- <input type="text" id="text" name="name"> -->


            <!-- Escolha da linguagem de programação -->
            <select id="' . $linguagens['Id'] . '" name="languageID">
            <option> Project language </option><br>
            <!-- <label for="Linguagens">Escolher a linguagem:</label><br> -->
            <?php
                $sql = "SELECT * FROM Linguagem";

                $q = $db->prepare($sql);
                $q->execute();
                $q->setFetchMode(PDO::FETCH_ASSOC);

                while ($linguagens = $q->fetch()) {
                    // echo '<input type="radio" id="' . $linguagens['Id'] . '" name="languageID" value="' . $linguagens['Id'] . '">';
                    // echo '<label for="' . $linguagens['Id'] . '">' . $linguagens['Linguagem'] . '</label><br>';
                    echo '<option value="'.$linguagens['Id'] .'" >' . $linguagens['Linguagem'] . '</option>';
                }
            ?>
            </select>

            <br>
            <br>
            <br>
            <br>   


<!-- Data de início e data de fim do projeto -->
<p>Project start date: <input class = "inputfile" type="text" id="datepicker" name="Begin_Date"></p>
            <p>Project end date: <input class = "inputfile" type="text" id="datepicker2" name="End_Date"></p>

            <br>
            <br>    

            <!-- Número de casos de teste que pretende -->
            <div class="NumCasosTeste">
                <label for="CasosTeste">Number of test cases (Min: 1 | Max: 8): </label>
                <input class = "inputfile" type="text" name="CasosTesteNum" id="textCasosTeste"><br><br>
                <!-- <input type="text" id="textCasosTeste" name="CasosTesteNum"> -->
                <input class = "button" type="button" value="Insert" id="textCasosTeste_Button" name="submit_Casos" onclick="addCasosTeste()">
            </div>

            <!-- Casos Teste -->
            <div id="DisplayCasosTeste" class="CasosTeste" >

        <!-- <table>
			<thead>
				<tr>
					<th width= "5%">Inputs</th>
					<th width= "5%">Outputs</th>
				</tr>
			</thead>
			<tbody>
                <tr>
                    <td><div id="InputDiv"></div></td>
                    <td><div id="OutputDiv"></div></td>
                </tr>
			</tbody>
		</table> -->

                <h3>Inputs</h3>

                <div id="InputDiv" >
                
                </div>

                <br>
                <h3>Outputs</h3>

                <div id="OutputDiv" >
                
                </div>
            </div>

      <!--      <label for="input">Input:</label>
            <input type="text" id="input" name="input"><br><br>
            <label for="output">Output:</label>
            <input type="text" id="output" name="output"><br><br>-->


            <br>
            <br>
    
        

            <!-- Funções que são proibídas no projeto -->
            <div class="FuncoesProibidasClass">
                <label>Number of prohibited functions: </label>
                <input class = "inputfile" type="text" id="TextFuncoesProibidas" name="FuncoesProibidas"><br><br>
                <input class = "button" type="button" value="Insert" id="FuncoesProibidas_Button" name="submit_FuncoesProibidas" onclick="addFuncoesProibidas()">
            </div>

            <!-- Funções proibídas -->
            <div id="DisplayFuncoesProibidas" class="CasosTeste" >
                <h3>Prohibited functions</h3>

                <div id="FuncoesProibidasInput" >
                
                </div>
            </div>

            <br>
            <br>    

            <input class = "button" type="submit" value="Submit" name="submit_Total">
        </form>

        </div>
   </body>
</html>


<script>

    function addFuncoesProibidas(){
       
        var numFuncoes = document.getElementById('TextFuncoesProibidas').value;
   
        var inputDiv = document.getElementById("FuncoesProibidasInput"); 

        for(var i = 0; i < numFuncoes; i++){
            var name = "funcaoP" + i;

            //Create an input type dynamically.   
            var elementInput = document.createElement("input");
            //Assign different attributes to the element. 
            elementInput.type = text;
            elementInput.value = " ";
            elementInput.name = name; 

            inputDiv.appendChild(elementInput);
        }
    }


    function addCasosTeste() {
        
        var numCasos = document.getElementById('textCasosTeste').value;

        if(numCasos > 0 && numCasos <9){
            var inputDiv = document.getElementById("InputDiv");
            var outputDiv = document.getElementById("OutputDiv");

            var tabspace = document.createElement("p");
            tabspace.innerHTML = "&nbsp";

            

            for(var i = 0; i < numCasos; i++){
                var name = "input" + i;

                //Create an input type dynamically.  
                
                var elementInput = document.createElement("input");
                //Assign different attributes to the element. 
                elementInput.type = text;
                elementInput.value = " ";
                elementInput.name = name; 
                
                inputDiv.appendChild(elementInput);
                inputDiv.appendChild(tabspace);
            }

            for(var i = 0; i < numCasos; i++){
                var name = "output" + i;

                console.log(name);

                //Create an input type dynamically.   
                var elementOutput = document.createElement("input");
                //Assign different attributes to the element. 
                elementOutput.type = text;
                elementOutput.value = " ";
                elementOutput.name = name; 

                outputDiv.appendChild(elementOutput);
                outputDiv.appendChild(tabspace);


            }
        }else{
            //Echo Insira um valor correto 
            //Limpa as divs caso já tenham sido escolhidas o num de inputs/outputs
            document.getElementById("InputDiv").innerHTML = "";
            document.getElementById("OutputDiv").innerHTML = "";

            document.getElementById("textCasosTeste").value = '';
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
                if(!empty($_POST['input0']) && !empty($_POST['output0'])){      //Tem pelo menos um input

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
                            
                            $user_id =  $_SESSION['user_Id'];  
                            $languageID = $_POST['languageID'];
                            $name= $_POST['name'];
                            $numCasos = $_POST['CasosTesteNum'];
                            $numFuncoes = $_POST['FuncoesProibidas'];

                            
                            /*Criação do Projeto*/
                            $sql = "INSERT INTO Projeto (Nome, Data_Projeto, Data_Limite, LinguagemID) VALUES ('$name', '$Data_Inicio', '$Data_Fim', '$languageID');";
                            $db->exec($sql);

                            /*Ir buscar o último ID que foi inserido
                            ,ou seja, o ID do último projeto*/
                            $stmt = $db->query("SELECT LAST_INSERT_ID()");
                            $ProjetoID = $stmt->fetchColumn();

                            /* Inserir os valores de casos de teste */
                            for ($i = 0; $i < $numCasos; $i++) {
                                $input = $_POST['input' . $i . ''];
                                $output = $_POST['output' . $i . ''];
                                
                                $sql_Casos_Teste = "INSERT INTO Casos_Teste (Input, Output, ProjetoID) VALUES ('$input', '$output', '$ProjetoID');";
                                $CasosTeste = $db->query($sql_Casos_Teste);
                            }


                            /* Inserir os valores das funções proibidas */
                            for ($i = 0; $i < $numFuncoes; $i++) {
                                $funcaoP = $_POST['funcaoP' . $i . ''];
                                
                                $funcoes_nao_permitidas = "INSERT INTO funcoes_nao_permitidas (Funcao, ProjetoID) VALUES ('$funcaoP', '$ProjetoID');";
                                $funcaoProibida = $db->query($funcoes_nao_permitidas);
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

    if(array_key_exists('button_logout', $_POST)) {
        button1();
    }
    function button1() {
        session_destroy();
        header("Location: login.php");
        die();
    }

?>