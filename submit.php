<?php
    ob_start();
    session_start();

    if (isset($_SESSION['user_Username']) && isset($_SESSION['user_Name'])) 
    {
        echo "Welcome to the member's area, " . $_SESSION['user_Username'] . "!";
    }else 
    {
        header("Location: login.php");
        die(); //pega
    }




/*Upload do ficheiro*/

    $file=$_FILES["fileToUpload"]["name"];
    $fileSize=$_FILES["fileToUpload"]["size"];
    $filePath=$_FILES["fileToUpload"]["tmp_name"];
    $todayDate = date("Y-m-d H:i:s");


    $ext = pathinfo($file, PATHINFO_EXTENSION);
    //$allowedTypes = array('application/zip', 'application/x-rar-compressed');
    
    echo "Inicio do upload</br>";
    
    if ($ext!="")
    {
        if ($ext = 'rar' || $ext = 'zip')
        {
            if ($_FILES["fileToUpload"]["error"] > 0)
            {
                echo "Return Code: " . $_FILES["fileToUpload"]["error"];
            }
            else
            {
                try{
                    if (file_exists("./Uploads/" . $_FILES["fileToUpload"]["name"]))
                    {
                        $alert= $_FILES["fileToUpload"]["name"] . " already exists. ";
                    }
                    else
                    {
                        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "./Uploads/".$file);
                        echo "Upload with sucess";    
                    }
                }           
                catch(PDOException $e)
                {
                    echo "Error in Uploading the File.";
                }
            }
        }
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


        $sql = "INSERT INTO ficheiro
        VALUES (1, '$file', $fileSize, 'c', '$filePath', $todayDate, $_SESSION['user_Id'], 1);";
        // use exec() because no results are returned
        $db->exec($sql);
        echo "Base de Dados atualizada";
    
    
        $db = null;
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }


?>