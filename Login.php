<!--    Conexão à Base de Dados     -->
<?php
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


   ob_start();

   session_start();

   // Tempo de sessão
   $now = time();
   if (isset($_SESSION['discard_after']) && $now > $_SESSION['discard_after']) {
      session_unset();
      session_destroy();
      session_start();
   }
   $_SESSION['discard_after'] = $now + 3600;

   if(isset($_SESSION['user_Id']))
   {
      header('location: index.php');
   }
?>


<html lang = "pt">
   
   <head>
      <title>Login</title>

      <style>
         body {
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #ADABAB;
         }
         
         .form-signin {
            max-width: 330px;
            padding: 15px;
            margin: 0 auto;
            color: #017572;
         }
         
         .form-signin .form-signin-heading,
         .form-signin .checkbox {
            margin-bottom: 10px;
         }
         
         .form-signin .checkbox {
            font-weight: normal;
         }
          
         .form-signin .form-control {
            position: relative;
            height: auto;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            padding: 10px;
            font-size: 16px;
         }
         
         .form-signin .form-control:focus {
            z-index: 2;
         }
         
         .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
            border-color:#017572;
         }
         
         .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-color:#017572;
         }
         
         h2{
            text-align: center;
            color: #017572;
         }
      </style>
      
   </head>
	
   <body>
      
      <h2>Enter Username and Password</h2> 
      <div class = "container form-signin">
    
        <?php
            $msg = '';
            


            if (isset($_POST['login']) && !empty($_POST['username']) 
            && !empty($_POST['password'])) {
                $username = trim($_POST['username']);
                $password = trim($_POST['password']);
                
                try {
                    $query = "select * from utilizador where Username=:username and Pass=:password";
                    $stmt = $db->prepare($query);
                    $stmt->bindParam('username', $username, PDO::PARAM_STR);
                    $stmt->bindValue('password', $password, PDO::PARAM_STR);
                    $stmt->execute();
                    $count = $stmt->rowCount();
                    $row   = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if($count == 1 && !empty($row)) {
                    /******************** Your code ***********************/
                        $_SESSION['user_Id']   = $row['Id'];
                        $_SESSION['usertype_Id']   = $row['Tipo_UtilizadorID'];
                        $_SESSION['user_Username'] = $row['Username'];
                        $_SESSION['user_Name'] = $row['Nome'];

                        $msg = "Login efetuado com sucesso";
                        header("Location: index.php");
                        die();
                        
                    } else {
                        $msg = "Invalid username and password!";
                    }
                } catch (PDOException $e) {
                    echo "Error : ".$e->getMessage();
                }
            } else {
              $msg = "Both fields are required!";
            }
         ?>

      </div>
      
      <div class = "container">
         <form class = "form-signin" role = "form" 
            action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); 
            ?>" method = "post">
            <h4 class = "form-signin-heading"><?php echo $msg; ?></h4>
            <input type = "text" class = "form-control" 
               name = "username" placeholder = "Username" 
               required autofocus></br>
            <input type = "password" class = "form-control"
               name = "password" placeholder = "Password" required>
            <button class = "btn btn-lg btn-primary btn-block" type = "submit" 
               name = "login">Login</button>
         </form>
      </div> 
      
   </body>
</html>