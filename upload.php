<?php
   ob_start();
   session_start();

    if (isset($_SESSION['user_Username']) && isset($_SESSION['user_Name'])) 
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
    <body>

        <form action="" method="post" enctype="multipart/form-data">
            <label for="Linguagens">Escolher a linguagem:</label><br>
            <?php
                $sql = "SELECT * FROM Linguagem";

                $q = $db->prepare($sql);
                $q->execute();
                $q->setFetchMode(PDO::FETCH_ASSOC);

                while ($linguagens = $q->fetch()) {
                    echo '<input type="radio" id="' . $linguagens['Id'] . '" name="language" value="' . $linguagens['Linguagem'] . '">';
                    echo '<label for="' . $linguagens['Linguagem'] . '">' . $linguagens['Linguagem'] . '</label><br>';
                }
            ?>
            <br>
            <br>
            <br>
            <br>     


            Submeter um ficheiro:
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Upload Image" name="submit">
        </form>

    </body>
</html>


<?php
if(isset($_POST['submit'])){
    if(!empty($_POST['language'])){

        if (isset($_SESSION['user_Username']) && isset($_SESSION['user_Name'])) 
        {
            echo "Welcome to the member's area, " . $_SESSION['user_Username'] . "!";
        }else 
        {
            header("Location: login.php");
            die(); //pega
        }



        /*Upload do ficheiro*/
        $user_id =  $_SESSION['user_Id'];  
        $language = $_POST['language'];
        $filename= time() . "_" .$_FILES["fileToUpload"]["name"];
        $fileSize=$_FILES["fileToUpload"]["size"];
        $filePath=$_FILES["fileToUpload"]["tmp_name"];
        $todayDate = date("Y-m-d H:i:s");


        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        //$allowedTypes = array('application/zip', 'application/x-rar-compressed');
        
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
              
            $sql = "INSERT INTO ficheiro (Nome, Tamanho, Linguagem, Destino, Data_Upload, UtilizadorID) VALUES ('$filename', '$fileSize', '$language', '$filePath', '$todayDate', '$user_id');";

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
        echo "Escolha uma das linguagens";
    }
}

?>