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
?>
            
<html lang = "pt">
   
   <head>
      <title>Index</title>
      <link rel="stylesheet" href="css/style.css">      
   </head>
	
   <body>
      <h2>Página Index</h2>  
      
      <?php
        if(array_key_exists('button_logout', $_POST)) {
            button1();
        }
        function button1() {
            session_destroy();
            header("Location: login.php");
            die();
        }
    ?>

      <form method="post">
        <input type="submit" name="button_logout"
                class="button" value="Logout" />
    </form>    
   </body>
</html>