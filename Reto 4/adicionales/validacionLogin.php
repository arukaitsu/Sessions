<?php

	session_start();
	$_SESSION["usuario"] = "";

	$return = "<a href='logout.php'>Volver al formulario</a>";

	if ( isset($_POST["login"]) ) {
		if (!empty($_POST["usuario"]) && !empty($_POST["clave"])) {
			$usuario = $_POST["usuario"];
			$clave = md5($_POST["clave"]);

			// Conectarse a MySQL
			$conn=mysqli_connect("localhost","root","","viviendas");
			mysqli_set_charset($conn, "utf8");

			if (mysqli_connect_errno()) {
				echo "Fallo al conectar con la BBDD";
				exit();
			}

			// Realizar consulta. Ponemos signos de interrogacion en los criterios WHERE porque mas adelante los pasamos por parametros

			$sql = "SELECT id, login, nombre, clave FROM usuarios WHERE login= ? and clave= ?";

			// Guardar resultado
			$resultado = mysqli_prepare($conn, $sql);

			// Devolvera true o false dependiento de si la consulta tiene exito o no
			$ok = mysqli_stmt_bind_param($resultado, "ss", $usuario, $clave);
			$ok = mysqli_stmt_execute($resultado);

			if ( $ok == false ) {
				echo "Error al ejecutar la consulta";
			} else {
				// Devuelve el resultado de la consulta. Cada parametro equivale a cada columna, excepto el primero, que se refiere a la consulta
				$ok = mysqli_stmt_bind_result($resultado, $resultId, $resultLogin, $resultNombre, $resultClave);

				// Almacena el resultado de la consulta (obligatorio para la funcion mysqli_stmt_num_rows)
				$ok = mysqli_stmt_store_result($resultado);
				$numrows = mysqli_stmt_num_rows($resultado);
				if ( $numrows == 1 ) {			
				// obtiene los resultados de la sentencia $result en las variables preparadas previamente (en mysqli_stmt_bind_result) 
					while ( mysqli_stmt_fetch($resultado) ) {
						$dbid = $resultId;
						$dblogin = $resultLogin;
						$dbnombre = $resultNombre;
						$dbclave = $resultClave;
					}
					// Si coincide la contraseña y usuario con el recibido de la consulta, inicia sesion
					if ( $dblogin == $usuario && $dbclave == $clave ) {
						$_SESSION["usuario"] = ucfirst($dbnombre);
						$_SESSION["id"] = ucfirst($dbid);
						header("Location:../Vprincipal.php");
					} 			
				} else {
					echo "Nombre de usuario o contraseña incorrectos";
					echo "<br>";
					echo $return;
				}
			}
			mysqli_stmt_close($resultado);

		} else {
			$error = "Debes rellenar todos los campos <br>";
			echo $error;
			echo $return;
		}

	// En caso de iniciar sesion por el boton de invitado
	} elseif ( isset($_POST["invitado"]) ) {
		$_SESSION["usuario"] = "Invitado";
		$_SESSION["id"] = "guest";
		header("Location:../Vprincipal.php");
	}
	?>