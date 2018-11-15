<?php 
	require_once('adicionales/controlSesion.php');
?>


<html>
<head>
	<title>VListado.php</title>
	<meta charset="UTF-8">
	<link href="https://fonts.googleapis.com/css?family=KoHo|Quicksand" rel="stylesheet">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="css/VListadoStyle.css">
</head>
<body>
	<br>
	<?php
		$edit = "";

		// Create connection
		require("funciones/conexionMysql.php");

		$sql = "SELECT vivienda_id, titulo, texto, metros_cuadrados, precio, categoria, fecha, imagen FROM viviendas ORDER BY vivienda_id";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			echo "<table><tr><th>Id</th><th>Título</th><th>Texto</th><th>Metros cuadrados</th><th>Precio</th><th>Categoría</th><th>Fecha</th><th>Imagen</th></tr>";
			// output data of each row
			while($row = $result->fetch_assoc()) {
				echo "<tr><td>" . $row["vivienda_id"] . "</td><td>" . $row["titulo"] . "</td><td>" . $row["texto"] . "</td><td>" . $row["metros_cuadrados"] . "m²" . "</td><td>" . $row["precio"] . "€" . "</td><td>" . $row["categoria"] . "</td><td>" . $row["fecha"] . "</td><td>" . "<image src=" . $row['imagen'] . " width='50px' height='50px'>" . "</td></tr>";
			}
			echo "</table>";
		} else {
			echo "Aún no hay ninguna vivienda registrada";
		}
		$conn->close();

		if (isset($_GET["editar"])) {
			if ( $_SESSION["usuario"] == "Invitado" ) {

				$edit = "<br><p id='editarInvitado'>Debes inciar sesión para editar viviendas";

			} else {
				$edit = "<br>
						<form id='editarVivienda' method='POST'>
						<hr><br>
						<p>Introduce el título de la vivienda que quieres editar</p>
						<br><br>
						<input type='number' name='id_vivienda'>
						<br><br>
						<input name='editarAceptar' type='submit' value='Aceptar'>
						<br><br><hr>
						</form>
						</br>";
			}
		}

		if ( isset($_POST["editarAceptar"]) ) {
				$viviendaId = intval($_POST["id_vivienda"]);
				require("funciones/conexionMysql.php");
				$query = mysqli_query($conn, "SELECT * FROM viviendas where vivienda_id=$viviendaId");
				$numrows = mysqli_num_rows($query);
				if ( $numrows == 1 ) {
					$edit = "<br><hr><br>
							<form method='POST' enctype='multipart/form-data'>
							<p>Escriba los nuevos datos de la vivienda " . $viviendaId . "</p>
							<input name='vivienda_id' type='hidden' value=$viviendaId>
							<br><br>
							<label for='nVTitulo'>Título: </label>
							<input name='nVTitulo' type='text' placeholder='Nuevo título...'>
							<br><br>
							<label for='nVTexto'>Texto: </label>
							<textarea name='nVTexto' type='textarea' placeholder='Nuevo texto...'></textarea>
							<br><br>
							<label for='nVMetros_cuadrados'>Metros cuadrados</label>
							<input name='nVMetros_cuadrados' type='number'>m²
							<br><br>
							<label for='nVCategoria'>Categoria: </label>
							<select name='nVCategoria'>
								<option value='costas'>costas</option>
								<option value='ofertas'>ofertas</option>
								<option value='promociones'>promociones</option>
							</select>
							<br><br>
							<label for='nVImagen'>
							<input type='file' name='nVImages'>
							<p>En cualquier caso, se borrará la imagen anterior.</p>
							<br><br>
							<input name='actualizarVivienda' type='submit' value='Actualizar vivienda'>
							<br>
							</form>
							<br><hr><br>";
				} else {
					$edit = "No existe ninguna vivienda con ese id";
				}
				mysqli_close($conn);
		}

		if ( isset($_POST["actualizarVivienda"]) ) {
			require("funciones/conexionMysql.php");
			require("funciones/calcularPrecio.php");
			$file = $_FILES['nVImages'];
			$nVId = intval($_POST['vivienda_id']);
			$nVtitulo = $_POST["nVTitulo"];
			$nVTexto = $_POST["nVTexto"];
			$nVMetrosCuadrados = $_POST["nVMetros_cuadrados"];
			if(file_exists($_FILES['nVImages']["tmp_name"]) || is_uploaded_file($_FILES['nVImages']["tmp_name"])) {
				$fileName = $file['name'];
				$fileTmpName = $file['tmp_name'];
				$fileExt = explode(".", $fileName);
				$fileActualExt = strtolower(end($fileExt));
				$fileNameNew = "vivienda" . $nVId . "." . $fileActualExt;
				$nVImagen = "img/".$fileNameNew;
				move_uploaded_file($fileTmpName, $nVImagen);
			} else {
				$nVImagen = "img/ico-fichero.gif";
			}					
			if ( isset($_POST["nVCategoria"]) ) {
				$nVCategoria = $_POST["nVCategoria"];
			} else {
				$nVCategoria = "costas";
			}
			$nVPrecio = calcularPrecio($nVMetrosCuadrados, $nVCategoria);
			$query = mysqli_query($conn, "UPDATE viviendas SET titulo='$nVtitulo', texto='$nVTexto', metros_cuadrados='$nVMetrosCuadrados', precio='$nVPrecio', categoria='$nVCategoria', imagen='$nVImagen' WHERE vivienda_id=$nVId");


			$fopen = fopen("viviendas/viviendas.txt", "a+");
			while ( ( $line = fgets($fopen) ) !== false  ) {
				$datosViviendaTxt = explode(" , ", $line);
				if ( $datosViviendaTxt[0] == $nVId ) {
					$contents = file_get_contents("viviendas/viviendas.txt");
					$contents = str_replace($line, '', $contents);
					file_put_contents("viviendas/viviendas.txt", $contents);
					$query = mysqli_query($conn, "SELECT * FROM viviendas WHERE vivienda_id=$nVId");
					$result = "";
					
					foreach ( mysqli_fetch_assoc($query) as $row ) {
						$result = $result . $row . " , ";
					}
					
					$result = rtrim($result, " , ") . "\n";
					fwrite( $fopen, $result);
					fclose($fopen);
				}
			}
			fclose($fopen);

			header("Location: VListado.php");

		}
	?>
	
	<br>
	<div id="identificationDiv">
		<p>Usted se ha identificado como <span><?php echo $_SESSION["usuario"]; ?></span> <a href='adicionales/logout.php'>(Salir)</a></p>
		<a href="Vprincipal.php">Volver al menú principal</a>
		<br>
		<br>
	</div>
	<div id="editarDiv">
		<a href="?editar">Editar vivienda <i class="fas fa-edit"></i></a>
		<div><?php echo $edit ?></div>
	</div>
	<br>

</body>
</html>