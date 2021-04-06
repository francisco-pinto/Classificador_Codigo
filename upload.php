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

        <form action="upload.php" method="post" enctype="multipart/form-data">
            Submeter um ficheiro:
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Upload Image" name="submit">
        

            <?php
                $file=$_FILES["fileToUpload"]["name"];

                $ext = pathinfo($file, PATHINFO_EXTENSION);
                //$allowedTypes = array('application/zip', 'application/x-rar-compressed');
                
                $alert = "Inicio do upload";
                
                if ($_FILES["fileToUpload"]["type"]!="")
                {
                    if ($ext = 'rar' || $ext = 'zip')
                    {
                        if ($_FILES["fileToUpload"]["error"] > 0)
                        {
                            $alert= "Return Code: " . $_FILES["fileToUpload"]["error"];
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
                                    $alert = "Upload with sucess";    
                                }
                            }           
                            catch(PDOException $e)
                            {
                                $alert = "Error in Uploading the File.";
                            }
                        }
                    }
                }   

            ?>

        </form>

    </body>
</html>


