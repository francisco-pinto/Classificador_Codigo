<html lang = "pt">
   
   <head>
      <title>Home Page</title>
      <link rel="stylesheet" href="css/style.css">
   </head>

    <div class="topnav">

        <a class="logo" href="index.php"><img src="css\Images\logoutad.png" alt = "logoutad"></a>

        <a class="logout funcionalidade" ><form method="post">
        <button class = "button" type = "logout" name = "button_logout">Logout</button>
        </form></a>

        <?php
           ob_start();
           session_start();

        if(isset($_SESSION['user_Username']) && isset($_SESSION['user_Name']) && $_SESSION['usertype_Id'] == 1)
        {
            echo "<a class='funcionalidade' href='notas.php'>Classificações</a>";
            echo "<a class='funcionalidade' href='upload.php'>Upload</a>";
        }else if(isset($_SESSION['user_Username']) && isset($_SESSION['user_Name']) && $_SESSION['usertype_Id'] == 2)
        {
            echo "<a class='funcionalidade' href='EditarProjeto.php'>Editar Projeto</a>";
            echo "<a class='funcionalidade' href='projeto.php'>Novo Projeto</a>";
        }
        ?>
        <a class="active funcionalidade"  href="index.php">Home</a>
    </div>


    
    <?php

    echo "<h2> Bem-Vindo, " . $_SESSION['user_Username'] . "! </h2>";

    if (!isset($_SESSION['user_Username']) && isset($_SESSION['user_Name'])) 
    {
        header("Location: login.php");
        die(); //pega
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
   </body>
</html>