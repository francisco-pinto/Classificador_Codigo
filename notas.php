<?php

    ob_start();
    session_start();

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


    if (isset($_SESSION['user_Username']) && isset($_SESSION['user_Name'])) 
    {
        echo "Welcome to the Classification's area, " . $_SESSION['user_Username'] . "!";
    }else 
    {
        header("Location: login.php");
        die();
    }
?>


<!DOCTYPE html>
<html>
    <body>
    <?php
    $query = $db->query('SELECT Nome, nota1.Classificacao FROM Utilizador m JOIN Nota nota1 ON m.NotaID = nota1.Id'); //pega
    ?>

    <table>
			<thead>
				<tr>
					<th>Utilizador</th>
					<th>Classificação</th>
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

    </body>
</html>