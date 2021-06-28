<?php
   ob_start();
   session_start();

    if (!isset($_SESSION['user_Username']) && isset($_SESSION['user_Name']) && $_SESSION['usertype_Id'] == 1) 
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
        echo "Conexão falhada: " . $e->getMessage();
    }
?>

<html lang = "pt">
    <head>
        <title>Upload</title>   
        <link rel="stylesheet" href="css/style.css">      
    </head>

    <body>

    <div class="topnav">

        <a class="logo" href="index.php"><img src="css\Images\logoutad.png" alt = "logoutad"></a>

        <a class="logout funcionalidade" ><form method="post">
        <button class = "button" type = "logout" name = "button_logout">Logout</button>
        </form></a>
        <a class=funcionalidade href=notas.php>Classificações</a>
        <a class="active funcionalidade"  href="index.php">Home</a>
    </div>

    <div class="container">
        <form action="" method="post" enctype="multipart/form-data">
            <br>
            <select id="' . $projeto['Id'] . '" name="projetoID">
            <option> Selecione um projeto </option>
            <?php
                $sql = "SELECT * FROM Projeto";

                $q = $db->prepare($sql);
                $q->execute();
                $q->setFetchMode(PDO::FETCH_ASSOC);

                while ($projeto = $q->fetch()) {
                    $linguagem_id = $_POST["LinguagemID"];
                    
                    if($projeto['Data_Limite'] >= date("Y-m-d H:i:s")){
                        echo '<option value="'.$projeto['Id'] .'" >' . $projeto['Nome'] . '</option>'; 
                    }
                }
            ?>
            </select>
            <br>
            <br>
            <br>
            <br>     


            Submeter um ficheiro:
            <br>
            <input class = "inputfile" type="file" name="fileToUpload" id="fileToUpload">

            <br><br>
            Nome do ficheiro Principal:
            <br>
            <input class = "inputfile" type="text" name="Filename" id="Filename">
            <br>
            <input class = "button" type="submit" value="Submeter Ficheiro" name="submit">
        </form>
        </div>
    </body>
</html>


<?php
if(isset($_POST['submit'])){
    if(!empty($_POST['projetoID']) && $_POST['projetoID'] != "Selecione um projeto"){
              
        /*Upload do ficheiro*/
        $user_id =  $_SESSION['user_Id'];  
        $filename= time() . "_" .$_FILES["fileToUpload"]["name"];
        $fileSize=$_FILES["fileToUpload"]["size"];
        $filePath=$_FILES["fileToUpload"]["tmp_name"];
        $todayDate = date("Y-m-d H:i:s");
        $projeto_id = $_POST["projetoID"];
        $MainFile = $_POST["Filename"];

        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        //$allowedTypes = array('application/zip', 'application/x-rar-compressed');
        $fileType = $ext;
        //echo $language;
        
        if ($ext!="")
        {
            if ($ext == 'rar' || $ext == 'zip')
            {
                if ($_FILES["fileToUpload"]["error"] > 0)
                {
                    echo "Return Code: " . $_FILES["fileToUpload"]["error"];
                    die();
                }
                else
                {   //Limitar o tamanho do upload (20 mb atualmente)
                    if($_FILES["fileToUpload"]["size"] < 20971520){
                        try{
                            if (!file_exists('./Uploads/')) {
                                mkdir('./Uploads/', 0777, true);
                            }
                            move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "./Uploads/".$filename);
                        }           
                        catch(PDOException $e)
                        {
                            echo "Error in Uploading the File.";
                            die();
                        }
                    }else{
                        echo "Ficheiro muito grande";
                        die();
                    } 
                }
            }else{
                echo "Escolha um ficheiro rar ou zip";
                die();
            }   
        }else{
            echo "Escolha um ficheiro";
            die();
        }   


    /*Introdução dos dados na BD*/

        $servername = "localhost";
        $username = "root";         //Default credencials wamp
        $password = "";

        try {
            $db = new PDO("mysql:host=$servername;dbname=Classificador_Codigo", $username, $password);
            // set the PDO error mode to exception
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                echo "Connected successfully";
            
            $sql = "INSERT INTO ficheiro (Nome, Tamanho, Tipo_Ficheiro, Destino, Data_Upload, UtilizadorID, ProjetoID, MainFile) VALUES ('$filename', '$fileSize', '$fileType', '$filePath', '$todayDate', '$user_id', '$projeto_id', '$MainFile');";

            // use exec() because no results are returned
            $db->exec($sql);
            die();
        
            $db = null;
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }    
    else{
        echo "Escolha um dos projetos";
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