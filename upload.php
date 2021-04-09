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
?>

<!DOCTYPE html>
<html>
    <body>

        <form action="" method="post" enctype="multipart/form-data">


            <!-- Adicionar lingaugens com base de Dados -->
            Escolher a linguagem:
            <input type="radio" id="c" name="language" value="c">
            <label for="c">C</label><br>
            <input type="radio" id="c++" name="language" value="c++">
            <label for="c++">C++</label><br>
            <input type="radio" id="c#" name="language" value="c#">
            <label for="c#">C#</label>
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
        $language = $_POST['language'];
        $file=$_FILES["fileToUpload"]["name"];
        $fileSize=$_FILES["fileToUpload"]["size"];
        $filePath=$_FILES["fileToUpload"]["tmp_name"];
        $todayDate = date("Y-m-d H:i:s");


        $ext = pathinfo($file, PATHINFO_EXTENSION);
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
        }else{
            echo "Escolha um ficheiro";
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


            $user_id =  $_SESSION['user_Id'];    

            $sql = "INSERT INTO ficheiro (Nome, Tamanho, Linguagem, Destino, Data_Upload, UtilizadorID, NotaID) VALUES ('$file', '$fileSize', '$language', '$filePath', '$todayDate', '$user_id', '1');";

            // use exec() because no results are returned
            $db->exec($sql);
            echo "Base de Dados atualizada";
        
        
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