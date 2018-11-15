<?php 
	require_once('adicionales/controlSesion.php');
?>

<html>
<head>
	<title>Vborrar.php</title>
	<meta charset="UTF-8">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans|Poiret+One" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/VborrarStyle.css">

</head>
<body>
	<?php

		$deleteLog = "";
		$conn = mysqli_connect("localhost", "root", "", "viviendas");
		$conn->set_charset("utf8");
		$query = mysqli_query($conn, "SELECT * from viviendas");
		$numrows = mysqli_num_rows($query);

		if ( $numrows > 0 ) {
			echo "<table><tr><th>Título</th><th>Texto</th><th>Categoría</th><th>Fecha</th><th>Imagen</th></tr>";
			while( $row = mysqli_fetch_assoc($query) ) {
				echo "<tr><td>" . $row["titulo"] . "</td><td>" . $row["texto"] . "</td><td>" . $row["categoria"] . "</td><td>" . $row["fecha"] . "</td><td>" . "<image src=" . $row['imagen'] . " width='50px' height='50px'>" . "</td><td><a href='?borrar=" . $row["vivienda_id"] . "'>Borrar</a></td></tr>";
			}
			if( isset($_GET['borrar']) ) {
				$borrarId = $_GET['borrar'];
				$deleteLog = "<hr>¿Estás seguro de que quieres eliminar la vivienda " . $borrarId . "?" . "<br>" . "<a href='?eleccion=si&id=" . $borrarId . "'> Si </a>" . " - " . "<a href='?eleccion=no&id=" . $borrarId . "'> No </a><hr>"; 
			}

			if ( isset($_GET['eleccion']) ) {
				if ( $_GET['eleccion'] == 'si' ) {
					$borrarId = $_GET["id"];
					$querySelect = mysqli_query($conn, "SELECT * FROM viviendas where vivienda_id='$borrarId'");
					$numrows = mysqli_num_rows($querySelect);
					if ($numrows == 1) {
						while ( $row = mysqli_fetch_assoc($querySelect) ) {
							$selectId = $row["vivienda_id"];
							$selectTitulo = $row["titulo"];
							$selectTexto = $row["texto"];
							$selectCategoria = $row["categoria"];
							$selectFecha = $row["fecha"];
							$selectImagen = $row["imagen"];
							}
						$queryInsertHistorica = mysqli_query($conn, "INSERT INTO viviendas_historica(vivienda_id, titulo, texto, categoria, fecha, imagen) VALUES('$selectId', '$selectTitulo', '$selectTexto', '$selectCategoria', '$selectFecha', '$selectImagen')");
						$borrarVivienda = mysqli_query($conn, "DELETE FROM viviendas where vivienda_id='$borrarId'");
						$queryViviendaEliminada = mysqli_query($conn, "SELECT * FROM viviendas_historica WHERE vivienda_id='$borrarId'");
						$numrows = mysqli_num_rows($queryViviendaEliminada);
						if( $numrows == 1 ) {
							while ( $row = mysqli_fetch_assoc($queryViviendaEliminada) ) {
								$_SESSION["deleteVivienda"] =  "<div id='registroBorrado'>" . "<h1>Vivienda eliminada</h1><br><hr><br>" . "<li>Título: " . $row['titulo'] . ".</li>" . "<li>Texto: " . $row['texto'] . ".</li>" . "<li>Categoría: " . $row['categoria'] . ".</li>" . "<li>Fecha: " . $row['fecha'] . ".</li>" . "<li>Imagen: " . $row['imagen'] . ".</li>" . "<li>E: " . $row['fecha_borrado'] . ".</li></div>";
								header("Location: adicionales/viviendaEliminada.php");
							}		
						}
					} else {
						echo "Se ha devuelto más de una fila.";
					}
				} elseif ( $_GET['eleccion'] == 'no' ) {
					header("Location: Vborrar.php");
				}
			mysqli_close($conn);
			}
		} else {
			"Aún no hay ninguna vivienda registrada";
		}		
	?>

	<div id="identificationDiv">
		<br>
		<p>Usted se ha identificado como <span><?php echo $_SESSION["usuario"]; ?></span> <a href='adicionales/logout.php'>(Salir)</a></p>
		<a href="Vprincipal.php">Volver al menú principal</a>
		<br>
	</div>
	<div style="width: 500px; margin: 0 auto">
		<p><?php echo $deleteLog; ?></p>
	</div>
	<br>
</body>
</html>