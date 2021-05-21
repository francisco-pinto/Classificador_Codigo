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
      <title>Configurar projeto</title>
      <link rel="stylesheet" href="css/style.css">       


        <!--De forma a conseguirmos obter um calendário-->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
   </head>
	
   <body>


   <div id="InformacoesProjeto">
        <h3>Insira os critérios de avaliação</h3>
            <?php
            
                if(!empty($_POST['projeto'])){
                    $_SESSION['projeto'] = $_POST['projeto'];
                }
                

                if(isset($_POST['submit_Projeto']) || isset($_POST['submit_Total'])){
                    if(!empty($_SESSION['projeto'])) {
                        
                        $projetoID = $_SESSION['projeto'];


                        echo "$projetoID";

                        /* Nome */
                        $query = $db->query(" SELECT Nome FROM projeto where Id='$projetoID' ");
                        $NomeProjeto = $query->fetchColumn();


                            
                        echo "<form action='' method='post' enctype='multipart/form-data' onsubmit='redirectEditarProjeto()'>
                        <label for='name'>Nome do Projeto:</label>
                        <input type='text' id='text' name='name' value='$NomeProjeto'><br><br><br><br>";
                        


                        /* Inputs */
                        $query = $db->query(" SELECT Input FROM casos_teste where ProjetoID='$projetoID' ");
                        $InputsArray = $query->fetchAll();

                        //echo 

                        /* Outputs */
                        $query = $db->query(" SELECT Output FROM casos_teste where ProjetoID='$projetoID' ");
                        $OutputArray = $query->fetchAll();


                        /* Processing data */
                        $OutputArray = array_map('reset', $OutputArray);
                        $InputsArray = array_map('reset', $InputsArray);

                        /* Casos Teste  Count */
                        $numCasosTeste = 0;
                        foreach($InputsArray as &$input){
                            $numCasosTeste++;
                        }


                        echo "<div class='NumCasosTeste'>
                            <label for='CasosTeste'>Números de Casos Teste (Min: 1 | Máx: 8) : </label>
                            <input type='text' id='textCasosTeste' name='CasosTesteNum' value='$numCasosTeste'><br><br>
                            <input type='button' value='Inserir' id='textCasosTeste_Button' name='submit_Casos' onclick='addCasosTeste()'>
                        </div>";
                        

                        echo "<div id='DisplayCasosTeste' class='CasosTeste' >
                
                            <h3>Inputs</h3>
                            <div id='InputDiv'>";

                        
                        $indiceNome = 0;
                        foreach($InputsArray as &$input){
                            echo "<input type='text' value='$input' id='textCasosTeste_Button' name='input$indiceNome'>";
                            $indiceNome++;
                        }
                        
                        echo "</div>
                        <h3>Outputs</h3>
                        <br>
                            <div id='OutputDiv'>";
                            
                        $indiceNome = 0;
                        foreach($OutputArray as &$output){
                            echo "<input type='text' value='$output' id='textCasosTeste_Button' name='output$indiceNome'>";

                            $indiceNome++;
                        }


                        /*echo "</div>
                        <label for='input'>Input:</label>
                        <input type='text' id='input' name='input'><br><br>
                        <label for='output'>Output:</label>
                        <input type='text' id='output' name='output'><br><br>-->


                        <br>
                        <br>";*/

                        echo "</form>"; //Finalizar o forms
                        //Projeto 
                        /*$ProjetoNome = $db->query("SELECT Nome FROM Projeto WHERE ID = '$projetoID'")->fetch();
                        echo $ProjetoNome;*/

                        //Casos Teste
                            //Num casos
                        //Funcoes não permitidas
                            //Num Funcoes

                        /*Escolha da linguagem de programação*/

                        echo"<br><br><br><label for='Linguagens'>Escolher a linguagem:</label><br>";
        
                        /* Ir buscar linguagem escolhida */
                        $query = $db->query(" SELECT LinguagemID FROM Projeto WHERE Id='$projetoID' ");
                        $LinguagemEscolhidaID = $query->fetch();

                        $query = $db->query(" SELECT Linguagem FROM linguagem WHERE Id='$LinguagemEscolhidaID[0]' ");
                        $LinguagemEscolhida = $query->fetch();

                        $sql = "SELECT * FROM Linguagem";

                        $q = $db->prepare($sql);
                        $q->execute();
                        $q->setFetchMode(PDO::FETCH_ASSOC);
        
                        while ($linguagens = $q->fetch()) {

                            if($linguagens['Linguagem'] == $LinguagemEscolhida[0]){
                                echo '<input type="radio" id="' . $linguagens['Id'] . '" name="languageID" checked value="' . $linguagens['Id'] . '">';
                            }else{
                                echo '<input type="radio" id="' . $linguagens['Id'] . '" name="languageID" value="' . $linguagens['Id'] . '">';
                            }

                            echo '<label for="' . $linguagens['Id'] . '">' . $linguagens['Linguagem'] . '</label><br>';
                        }
                        
                        echo "<br><br><br><br>";
            
            
                        /* Data de início e data de fim do projeto */
                        $queryDataInicio = $db->query(" SELECT Data_Projeto FROM Projeto WHERE Id='$projetoID' ");
                        $DataInicio = $queryDataInicio->fetch();

                        $Data_Inicial = date('d-m-Y', strtotime($DataInicio[0]));
                        

                        $queryDataInicio = $db->query(" SELECT Data_Limite FROM Projeto WHERE Id='$projetoID' ");
                        $DataFim = $queryDataInicio->fetch();

                        $Data_Final = date('d-m-Y', strtotime($DataFim[0]));


                        echo "<p>Data de início de Projeto: <input type='text' id='datepicker' name='Begin_Date' value='$Data_Inicial'></p>
                            <br><br>
                            <p>Data de Fim de Projeto: <input type='text' id='datepicker2' name='End_Date' value='$Data_Final'></p>
                
                            <br>
                            <br> ";
                        


                        /* Inputs */
                        $query = $db->query(" SELECT Funcao FROM funcoes_nao_permitidas where ProjetoID='$projetoID' ");
                        $FuncoesArray = $query->fetchAll();

                        /* Processing data */
                        $FuncoesArray = array_map('reset', $FuncoesArray);

                        /* Casos Teste  Count */
                        $numFuncoesProibidas = 0;
                        foreach($FuncoesArray as &$input){
                            $numFuncoesProibidas++;
                        }




                        /* Funções que são proibídas no projeto */
                        echo "<div class='FuncoesProibidasClass'>
                            <label>Nomes das funções proibidas a utilizar: </label>
                            <input type='text' id='TextFuncoesProibidas' name='FuncoesProibidas' value='$numFuncoesProibidas'><br><br>
                            <input type='button' value='Inserir' id='FuncoesProibidas_Button' name='submit_FuncoesProibidas' onclick='addFuncoesProibidas()'>
                        </div>";
            
                        echo "<!-- Funções proibídas -->
                        <div id='DisplayFuncoesProibidas' class='CasosTeste' >
                            <h3>Funções proibidas</h3>
            
                            <div id='FuncoesProibidasInput' >";
                                $numFuncoes = 0;
                                foreach($FuncoesArray as &$funcao){
                                    echo "<input type='text' value='$funcao' id='textCasosTeste_Button' name='funcaoP$numFuncoes'>";

                                    $numFuncoes++;
                                }
                        echo "</div>
                        </div>";
            
                        echo "<br>
                        <br>    
                        <input type='submit' value='Submeter' name='submit_Total'>
                    </form>";
                    }else{
                        echo "Projeto ID desconhecido";
                    }
                }else{

                }



                    
            ?>
        </div>
   </body>
</html>


<script>

    function redirectEditarProjeto(){
        window.location.href = "/Projeto/EditarProjeto.php";
    }

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
                        
                            echo "Início da colocação do projeto";

                            
                            $user_id =  $_SESSION['user_Id'];  
                            $languageID = $_POST['languageID'];
                            $name= $_POST['name'];
                            $numCasos = $_POST['CasosTesteNum'];
                            $numFuncoes = $_POST['FuncoesProibidas'];

                            
                            /*Criação do Projeto*/
                            $sql = "UPDATE Projeto SET 
                                    Nome = '$name',
                                    Data_Projeto = '$Data_Inicio',
                                    Data_Limite = '$Data_Fim',
                                    LinguagemID = '$languageID'";

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
                            echo "Language ID: $languageID <br>";                           

                            $Begin_Date = str_replace('/', '-', $Begin_Date);
                            $End_Date = str_replace('/', '-', $End_Date);

                            $Data_Inicio = strtotime($Begin_Date);
                            $Data_Fim = strtotime($End_Date);*/
                            /*$Begin_DateTimestamp = date('Y-m-d H:i:s', strtotime($Begin_Date));  
                            $End_DateTimestamp = date('Y-m-d H:i:s', strtotime($End_Date));  */

                            
                            // use exec() because no results are returned
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