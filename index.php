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

   <header id="header">
        <div class="container">

            <div class="logo float-left">
                <a href="index.php">
                    <img src="Images/logoutad.png"  alt = "logoutad"/>
                </a> 
            </div>

            <nav class="main-nav float-right d-none d-lg-block">
                <ul>
                <li><h2>Página Index</h2></li>
                    <!-- Se for aluno -->
                    <li><a href="upload.php">Upload</a></li>
                    <li><a href="notas.php">Classificações</a></li>
                    <!-- Se for prof -->
                    <li><a href="projeto.php">Novo Projeto</a></li>
                    <li><a href="EditarProjeto.php">Editar Projeto</a></li>
                <li>
                    <form method="post">
                    <button class = "button" type = "logout" name = "button_logout">Logout</button>
                    </form>  
                </li>
                </ul>
            </nav>
        </div>
    </header>
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
   </body>
</html>