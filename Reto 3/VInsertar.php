<?php 
	require_once('adicionales/controlSesion.php');
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
		$tituloErr = $textoErr = "";
		$titulo = $texto = "";
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

			if ( !empty($_POST["titulo"]) && !empty($_POST["texto"]) ) {

				$conn = mysqli_connect("localhost", "root", "", "viviendas");
				mysqli_set_charset($conn, "utf8");

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
								if ($fileSize < 1000000) {
									$fileNameNew = uniqid("", true).".".$fileActualExt; // uniqid: genera un ID unico prefijado basado en la hora actual en microsegundos
									$fileDestination = "img/".$fileNameNew; // definimos el destino donde se almacenara la imagen subida
									$query = mysqli_query($conn, "INSERT INTO viviendas (titulo, texto, categoria, imagen) values('$titulo', '$texto', '$categoria', '$fileDestination')");
									move_uploaded_file($fileTmpName, $fileDestination);
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
						$query = mysqli_query($conn, "INSERT INTO viviendas (titulo, texto, categoria, imagen) values('$titulo', '$texto', '$categoria', 'img/ico-fichero.gif')");
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
		<label for="categoria">Categoría</label>
		<select>
			<option name="categoria">costas</option>
			<option name="categoria">ofertas</option>
			<option name="categoria">promociones</option>
		</select>
		<br><br>
		<label for="imagen">Imagen:</label>
		<br>
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