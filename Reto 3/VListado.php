<?php 
	require_once('adicionales/controlSesion.php');
?>


<html>
<head>
	<title>VListado.php</title>
	<meta charset="UTF-8">
	<link href="https://fonts.googleapis.com/css?family=KoHo" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/VListadoStyle.css">
</head>
<body>
	<?php

		$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "viviendas";

		// Create connection
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		$conn->set_charset("utf8");

		// Check connection
		if (!$conn) {
			die("Error de conexión: " . mysqli_connect_error());
		}

		$sql = "SELECT titulo, texto, categoria, fecha, imagen FROM viviendas ORDER BY fecha";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			echo "<table><tr><th>Título</th><th>Texto</th><th>Categoría</th><th>Fecha</th><th>Imagen</th></tr>";
			// output data of each row
			while($row = $result->fetch_assoc()) {
				echo "<tr><td>" . $row["titulo"] . "</td><td>" . $row["texto"] . "</td><td>" . $row["categoria"] . "</td><td>" . $row["fecha"] . "</td><td>" . "<image src=" . $row['imagen'] . " width='50px' height='50px'>" . "</td></tr>";
			}
		} else {
			echo "Aún no hay ninguna vivienda registrada";
		}
		$conn->close();
	?>
	<br>
	<div id="identificationDiv">
		<p>Usted se ha identificado como <span><?php echo $_SESSION["usuario"]; ?></span> <a href='adicionales/logout.php'>(Salir)</a></p>
		<a href="Vprincipal.php">Volver al menú principal</a>
		<br>
		<br>
	</div>

	

</body>
</html>