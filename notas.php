<html lang = "pt">
    <head>
        <title>Notas</title>
        <link rel="stylesheet" href="css/style.css">      
    </head>

    <?php
    ob_start();
    session_start();

    //Verificação de professor ou aluno
    if (!isset($_SESSION['user_Username']) && isset($_SESSION['user_Name']) && $_SESSION['usertype_Id'] == 1)
    {
        header("Location: login.php");
        die(); //pega
    }

    $servername = "localhost";
    $username = "root";         //Default credencials wamp
    $password = "";

    try {
        $db = new PDO("mysql:host=$servername;dbname=Classificador_Codigo", $username, $password);
        // set the PDO error mode to exception
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    ?>

    <body>
    <div class="topnav">
        <a class="logo" href="index.php"><img src="css\Images\logoutad.png" alt = "logoutad"></a>

        <a class="logout funcionalidade" ><form method="post">
        <button class = "button" type = "logout" name = "button_logout">Logout</button>
        </form></a>

        <a class=funcionalidade href=upload.php>Upload</a>
        <a class="active funcionalidade"  href="index.php">Home</a>
    </div>

    <?php
    $query = $db->query('SELECT Classificacao, utilizador.Nome FROM Nota nota JOIN Utilizador utilizador ON nota.UtilizadorID = utilizador.Id'); //pega
    ?>

    <div class="container">
    <table>
			<thead>
				<tr>
					<th width= "15%">Utilizador</th>
					<th width= "5%">Classificação</th>
				</tr>
			</thead>
			<tbody>
            <?php
                while($data = $query->fetch(PDO::FETCH_ASSOC))
                {
                    echo "<tr>";
                    echo "<td>".$data['Nome']."</td>";
                    echo "<td>".$data['Classificacao']."</td>";
                }
            ?>
			</tbody>
		</table>
    </div>

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