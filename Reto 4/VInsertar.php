<?php 
	require_once('adicionales/controlSesion.php');
	require_once('adicionales/controlInvitados.php');
?>

<html>
<head>
	<title></title>
	<meta charset="UTF-8">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans|Poiret+One" rel="stylesheet">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="css/VInsertarStyle.css">
</head>
<body>

	<?php
		$tituloErr = $textoErr = $metros_cuadradosErr = "";
		$titulo = $texto = $metros_cuadrados = "";
		$exito = "";

		if ( isset($_POST["insertarVivienda"]) ) {
			if ( isset($_POST["categoria"]) ) {
				$categoria = $_POST["categoria"];
			} else {
				$categoria = "costas";
			}

			if ( empty($_POST["titulo"]) ) {
				$tituloErr = "Tiene que escribir un título";
			} else {
				$titulo = $_POST["titulo"];
			}
			if ( empty($_POST["texto"]) ) {
				$textoErr = "Tiene que escribir una descripción";
			} else {
				$texto = $_POST["texto"];
			}
			if ( empty($_POST["metros_cuadrados"]) ) {
				$metros_cuadradosErr = "Especifique los metros cuadrados de la vivienda";
			} else {
				$metros_cuadrados = $_POST["metros_cuadrados"];
			}

			if ( !empty($_POST["titulo"]) && !empty($_POST["texto"]) && !empty($_POST["metros_cuadrados"])) {
				$fopen = fopen("viviendas/viviendas.txt", "a+") or die("No se ha encontrado el archivo de texto de viviendas");
				
				require_once("funciones/calcularPrecio.php");
				$precio = calcularPrecio($metros_cuadrados, $categoria);
				require_once("funciones/conexionMysql.php");

				$query = mysqli_query($conn, "SELECT * FROM viviendas ORDER BY vivienda_id DESC LIMIT 1");
									$numrows = mysqli_num_rows($query);
									if ($numrows == 1) {
										while ( $row = mysqli_fetch_assoc($query) ) {
											$nuevaId = $row["vivienda_id"] + 1;
										}
									}

				$query = mysqli_query($conn, "SELECT * FROM viviendas where titulo='$titulo'");
				$numrows = mysqli_num_rows($query);
				
				if ( $numrows == 0 ) {				
					if(file_exists($_FILES['foto']['tmp_name']) || is_uploaded_file($_FILES['foto']['tmp_name'])) {
						// La variable superglobal $_FILES recoge todos los datos del archivo subido. Esta informacion se guarda en un array con 5 valores: nombre, tipo, nombre temporal, errores y tamaño
						$file = $_FILES['foto']; // -----> el archivo que hemos subido
						$fileName = $file['name']; // ---> nombre del archivo
						$fileType = $file['type']; // --> el tipo
						$fileTmpName = $file['tmp_name']; // ->el nombre temporal
						$fileError = $file["error"]; // ---> si ha habido algun error en la subida del archivo
						$fileSize = $file['size'];// ---> el tamaño

						$fileExt = explode(".", $fileName); // explode(): divide un string en varios strings. El primer parametro que le pasamos es el delimitador (por donde se dividira el string) y el seguno indica la string. Una vez divido, el string que teniamos se habra convertido en un array con dos o mas valores. En este caso, el nombre del archivo que subamos se convertia en un array de dos valores, siendo el primero el nombre y el segundo la extension

						$fileActualExt = strtolower(end($fileExt)); // Dado que hemos transformado el nombre del archivo en un array, con end() indicamos que cogeremos el ultimo valor del array. strtolower(): convierte la string a minusculas

						$allowed = array('jpg', 'jpeg', 'png'); // un array que en el que guardaremos los formatos de los archivos que permitiremos subir

						if (in_array($fileActualExt, $allowed)) { // comprueba si un valor existe en una array. Le pasamos dos parametros, el primero es el valor que queremos comprobar, y el segundo la opcion u opciones. Si el valor de $fileActualExt existe en la variable $allowed (es decir, que el contenido de $fileActualExt es igual a alguno de los valores del array $allowes), el output que nos devolvera sera true {
							if ($fileError === 0) { // ----> si no da ningun error la subida
								if ($fileSize < 1000000000) {
									$fileNameNew = "vivienda" . $nuevaId. "." . $fileActualExt; // el nombre del archivo sera vivienda + la mayor vivienda_id de la base de datos +1 y el formato de la imagen subida
									$fileDestination = "img/".$fileNameNew; // definimos el destino donde se almacenara la imagen subida
									$query = mysqli_query($conn, "INSERT INTO viviendas (vivienda_id, titulo, texto, metros_cuadrados, precio, categoria, imagen) values('$nuevaId', '$titulo', '$texto', $metros_cuadrados, $precio, '$categoria', '$fileDestination')");
									move_uploaded_file($fileTmpName, $fileDestination);
									$query = mysqli_query($conn, "SELECT * FROM viviendas WHERE vivienda_id=$nuevaId");
									
									$result = "";
									foreach ( mysqli_fetch_assoc($query) as $row ) {
										$result = $result . $row . " , ";
									}

									$result = rtrim($result, " , ") . "\n";
									fwrite( $fopen, $result);
									fclose($fopen);
									$exito = "La nueva vivienda se ha añadido con éxito!";								
								} else {
									echo "La imagen es demasiado grande";
								}
							} else {
								echo "Hubo un error en la subida del archivo";
							}
						} else {
							echo "No puedes cargar este archivo";
						}
					} else {
						$query = mysqli_query($conn, "INSERT INTO viviendas (vivienda_id, titulo, texto, metros_cuadrados, precio, categoria, imagen) values('$nuevaId', '$titulo', '$texto', '$metros_cuadrados', '$precio', '$categoria', 'img/ico-fichero.gif')");

						$query = mysqli_query($conn, "SELECT * FROM viviendas WHERE vivienda_id=$nuevaId");
									
						$result = "";
						foreach ( mysqli_fetch_assoc($query) as $row ) {
							$result = $result . $row . " , ";
						}

						$result = rtrim($result, " , ") . "\n";
						fwrite( $fopen, $result);
						fclose($fopen);
						$exito = "La nueva vivienda se ha añadido con éxito!";
					}
				} else {
					$tituloErr = "Ya existe una vivienda con ese nombre";
				}
				mysqli_close($conn);
			}
		}
	?>

	<br>
	<form id="form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
		<div id="identificationDiv">
			<p>Usted se ha identificado como <span><?php echo $_SESSION["usuario"]; ?></span> <a href='adicionales/logout.php'>(Salir)</a></p>
			<a href="Vprincipal.php"><i class="fas fa-arrow-circle-left"></i> VOLVER AL MENÚ PRINCIPAL</a>
		</div>
		<br><hr><br>
		<h1>Inserción de nueva vivienda</h1>
		<br>
		<label for="titulo">Título: *</label>
		<span><?php echo $tituloErr; ?></span>
		<br>
		<input type="text" name="titulo">
		<br><br>
		<label>Metros cuadrados: *</label>
		<span><?php echo $metros_cuadradosErr; ?></span>
		<br>
		<input type="number" name="metros_cuadrados">
		<br><br>
		<label for="categoria">Categoría</label>
		<select name='categoria'>
			<option value='costas'>costas</option>
			<option value='ofertas'>ofertas</option>
			<option value='promociones'>promociones</option>
		<br><br>
		<label for="foto">Imagen:</label>
		<br><br>
		<input type="file" name="foto">
		<br><br>
		<label for="texto">Texto: *</label>
		<span><?php echo $textoErr; ?></span>
		<br>
		<textarea name="texto" rows="5" cols="50" placeholder="Escribe una pequeña descripción..."></textarea>
		<br><br>
		<input id="submitButton" type="submit" name="insertarVivienda" value="Insertar vivienda">
		<br><br>
		<p>NOTA: los datos marcados con (*) deben ser rellenados obligatoriamente.</p>
		<br>
		<a href="Vprincipal.php"><i class="fas fa-arrow-circle-left"></i> VOLVER AL MENÚ PRINCIPAL</a>
	</form>
	<br>
	<div id="exito"><?php echo $exito ?></div>

</body>
</html>