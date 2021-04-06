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
                $allowedTypes = array('application/zip', 'application/x-rar-compressed');
                
                $alert = "Inicio do upload";
                
                if ($_FILES["fileToUpload"]["type"]!="")
                {
                    if (in_array($_FILES["fileToUpload"]["type"], $allowedTypes))
                    {
                        if ($_FILES["fileToUpload"]["error"] > 0)
                        {
                            $alert= "Return Code: " . $_FILES["fileToUpload"]["error"];
                        }
                        else
                        {
                            try{
                                if (file_exists("../Uploads/" . $_FILES["fileToUpload"]["name"]))
                                {
                                    $alert= $_FILES["fileToUpload"]["name"] . " already exists. ";
                                }
                                else
                                {
                                    //I renamed the file into FILENAME
                                    $newfilename = 'FILENAME.'.pathinfo($file, PATHINFO_EXTENSION);
                                    //file permission
                                    mkdir($newfilename , 0777, true);
                                    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "../Uploads/".$newfilename);
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


