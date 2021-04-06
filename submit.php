<?php
    $file=$_FILES["fileToUpload"]["name"];

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

?>