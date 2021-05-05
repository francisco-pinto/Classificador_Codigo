<?php
   ob_start();
   session_start();

    if (isset($_SESSION['user_Username']) && isset($_SESSION['user_Name']) && $_SESSION['usertype_Id'] == 1) 
    {
        echo "Welcome to the Upload's area, " . $_SESSION['user_Username'] . "!";
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

<!DOCTYPE html>
<html>

    <head>
        <title>Upload</title>   
        <link rel="stylesheet" href="css/style.css">      
    </head>

    <body>

        <form action="" method="post" enctype="multipart/form-data">
            <label for="Projeto">Escolher o projeto:</label><br>
            <?php
                $sql = "SELECT * FROM Projeto";

                $q = $db->prepare($sql);
                $q->execute();
                $q->setFetchMode(PDO::FETCH_ASSOC);

                while ($projeto = $q->fetch()) {
                    $linguagem_id = $projeto["LinguagemID"];
                    echo '<input type="radio" id="' . $projeto['Id'] . '" name="projetoID" value="' . $projeto['Id'] . '">';
                    echo '<label for="' . $projeto['Id'] . '">' . $projeto['Nome'] . '</label><br>'; 
                }
            ?>
            <br>
            <br>
            <br>
            <br>     


            Submeter um ficheiro:
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Upload File" name="submit">
        </form>

    </body>
</html>


<?php
if(isset($_POST['submit'])){
    if(!empty($_POST['projetoID'])){

        /*Upload do ficheiro*/
        $user_id =  $_SESSION['user_Id'];  
        $filename= time() . "_" .$_FILES["fileToUpload"]["name"];
        $fileSize=$_FILES["fileToUpload"]["size"];
        $filePath=$_FILES["fileToUpload"]["tmp_name"];
        $todayDate = date("Y-m-d H:i:s");
        $projeto_id = $_POST["projetoID"];
        


        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        //$allowedTypes = array('application/zip', 'application/x-rar-compressed');
        $fileType = $ext;
        echo "Inicio do upload</br>";
        //echo $language;
        
        if ($ext!="")
        {
            if ($ext = 'rar' || $ext = 'zip')
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
              
            $sql = "INSERT INTO ficheiro (Nome, Tamanho, Tipo_Ficheiro, Destino, Data_Upload, UtilizadorID, ProjetoID, LinguagemID) VALUES ('$filename', '$fileSize', '$fileType', '$filePath', '$todayDate', '$user_id', '$projeto_id', '$linguagem_id');";

            // use exec() because no results are returned
            $db->exec($sql);
            echo "<br><br><br>Base de Dados atualizada";
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

?>