<?php 
	require_once('adicionales/controlSesion.php');
	require_once('adicionales/controlInvitados.php');
?>

<html>
<head>
	<title>Vborrar.php</title>
	<meta charset="UTF-8">
	<link href="https://fonts.googleapis.com/css?family=KoHo" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/VborrarStyle.css">

</head>
<body>
	<?php

		$delete = "";
		$deleteLog = "";
		require("funciones/conexionMysql.php");
		$query = mysqli_query($conn, "SELECT * from viviendas");
		$numrows = mysqli_num_rows($query);

		if ( $numrows > 0 ) {
			echo "<br><table><tr><th>Id de la vivienda</th><th>Título</th><th>Texto</th><th>Metros cuadrados</th><th>Precio</th><th>Categoría</th><th>Fecha</th><th>Imagen</th></tr>";
			while( $row = mysqli_fetch_assoc($query) ) {
				echo "<tr><td align='center'>" . $row["vivienda_id"] . "</td><td>" . $row["titulo"] . "</td><td>" . $row["texto"] . "</td><td>" . $row["metros_cuadrados"] . "m²" .  "</td><td>" . $row["precio"] . "€" . "</td><td>" . $row["categoria"] . "</td><td>" . $row["fecha"] . "</td><td>" . "<image src=" . $row['imagen'] . " width='50px' height='50px'>" . "</td><td><a href='?borrar=" . $row["vivienda_id"] .  "'>Borrar</a></td></tr>";
			}
			echo "</table>";


		// Borrado individual
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
							$selectMetrosCuadrados = $row["metros_cuadrados"];
							$selectPrecio = $row["precio"];
							$selectCategoria = $row["categoria"];
							$selectFecha = $row["fecha"];
							$selectImagen = $row["imagen"];
							}
						$queryInsertHistorica = mysqli_query($conn, "INSERT INTO viviendas_historica(vivienda_id, titulo, texto, metros_cuadrados, precio, categoria, fecha, imagen) VALUES('$selectId', '$selectTitulo', '$selectTexto', '$selectMetrosCuadrados', '$selectPrecio', '$selectCategoria', '$selectFecha', '$selectImagen')");
						$borrarVivienda = mysqli_query($conn, "DELETE FROM viviendas where vivienda_id='$borrarId'");
						$queryViviendaEliminada = mysqli_query($conn, "SELECT * FROM viviendas_historica WHERE vivienda_id='$borrarId'");
						$numrows = mysqli_num_rows($queryViviendaEliminada);
						if( $numrows == 1 ) {
							while ( $row = mysqli_fetch_assoc($queryViviendaEliminada) ) {
								$_SESSION["deleteVivienda"] =  "<div id='registroBorrado'>" . "<h1>Vivienda eliminada</h1><br><hr><br>" . "<li>Título: " . $row['titulo'] . ".</li>" . "<li>Texto: " . $row['texto'] . ".</li>" . "<li>Metros cuadrados: " . $row["metros_cuadrados"] . "m².</li>" . "<li>Precio: " . $row['precio'] . "€" . ".</li>" . "<li>Categoría: " . $row['categoria'] . ".</li>" . "<li>Fecha: " . $row['fecha'] . ".</li>" . "<li>Imagen: " . $row['imagen'] . ".</li>" . "<li>E: " . $row['fecha_borrado'] . ".</li></div>";
								header("Location: adicionales/viviendaEliminada.php");
							}		
						}
						$fopen = fopen("viviendas/viviendas.txt", "a+");
						while ( ( $line = fgets($fopen) ) !== false  ) {
							$datosViviendaTxt = explode(" , ", $line);
							if ( $datosViviendaTxt[0] == $selectId ) {
								$contents = file_get_contents("viviendas/viviendas.txt");
								$contents = str_replace($line, '', $contents);
								file_put_contents("viviendas/viviendas.txt", $contents);
							}
						}
						fclose($fopen);

					} else {
						echo "Se ha devuelto más de una fila.";
					}
					mysqli_close($conn);
				} elseif ( $_GET['eleccion'] == 'no' ) {
					header("Location: Vborrar.php");
				}
			}

		// 	Borrado multiple 
			if ( isset($_GET["borradoMultiple"]) ) {
				$delete =  "<br>
							<form method='POST'>
								<p>Escribe las viviendas (id) que quieres borrar separadas por un guión.</p>
								<p>P.ej. 1-3-4</p>
								<br>
								<input name='idsViviendas' type='text' placeholder='1-3-22-44'>
								<br><br>
								<input type='submit' name='aceptarBorrar' value='Aceptar'>
							</form>";
			}

			if ( isset($_POST["aceptarBorrar"]) ) {
				if ( !empty($_POST["idsViviendas"]) ) {
					require("funciones/conexionMysql.php");
					$inputIdViviendas = $_POST["idsViviendas"];
					$idViviendasArray = explode("-", $inputIdViviendas);
					$queryCondition = " or vivienda_id=";
					$idViviendasString = implode($queryCondition, $idViviendasArray);
					
					$query = mysqli_query($conn, "SELECT * FROM viviendas WHERE vivienda_id=$idViviendasString");

					$numrows = mysqli_num_rows($query);
					if ( $numrows == 0 ) {
						echo "No se ha encontrado ninguna vivienda";
					}else {
						$fopen = fopen("viviendas/viviendas.txt", "a+");
						while ( ( $line = fgets($fopen) ) !== false  ) {
							$datosViviendaTxt = explode(" , ", $line);
							if ( in_array($datosViviendaTxt[0], $idViviendasArray) ) {
								$contents = file_get_contents("viviendas/viviendas.txt");
								$contents = str_replace($line, '', $contents);
								file_put_contents("viviendas/viviendas.txt", $contents);
							}
						}
						fclose($fopen);
	 					$query = mysqli_query($conn, "DELETE FROM viviendas WHERE vivienda_id=$idViviendasString");
	 					header("Location: Vborrar.php");
					}
				} else {
					false;
				}
			}
			
		} else {
			echo "Aún no hay ninguna vivienda registrada";
		}		
	?>

	<br>
	<div id="borradoDiv">
		<a href="?borradoMultiple">Borrado multiple</a>
		<div><?php echo $delete ?></div>
	</div>

	<div style="width: 500px; margin: 0 auto">
		<p><?php echo $deleteLog; ?></p>
	</div>

	<br>

	<div id="identificationDiv">
		<p>Usted se ha identificado como <span><?php echo $_SESSION["usuario"]; ?></span> <a href='adicionales/logout.php'>(Salir)</a></p>
		<a href="Vprincipal.php">Volver al menú principal</a>
		<br>
	</div>

</body>
</html>