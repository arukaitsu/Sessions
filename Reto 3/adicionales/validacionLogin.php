<?php

	session_start();
	$_SESSION["usuario"] = "";

	$return = "<a href='logout.php'>Volver al formulario</a>";

	if ( isset($_POST["login"]) ) {
		if (!empty($_POST["usuario"]) && !empty($_POST["clave"])) {
			$usuario = $_POST["usuario"];
			$clave = $_POST["clave"];

			// Conectarse a MySQL
			$conn=mysqli_connect("localhost","root","","viviendas");
			mysqli_set_charset($conn, "utf8");

			// Realizar consultas
			$query = mysqli_query($conn, "SELECT * FROM usuarios WHERE login='$usuario' AND clave='$clave'");

			$numrows = mysqli_num_rows($query);
			if ( $numrows!=0 ) {
				while ( $row=mysqli_fetch_assoc($query) ) {
					$dbid = $row["id"];
					$dblogin = $row["login"];
					$dbclave = $row["clave"];
					$dbnombre = $row["nombre"];
				}
				if ( $usuario == $dblogin && $clave == $dbclave ) {
					$_SESSION["usuario"] = ucfirst($dbnombre);
					$_SESSION["id"] = ucfirst($dbid);
					header("Location:../Vprincipal.php");
				}
			} else {
				echo "Nombre de usuario o contraseña incorrectos";
				echo "<br>";
				echo $return;
			}
		mysqli_close($conn);
		} else {
			$error = "Debes rellenar todos los campos <br>";
			echo $error;
			echo $return;
		}

	}
	?>