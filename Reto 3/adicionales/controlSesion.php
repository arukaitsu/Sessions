<?php
	session_start();
	$return = "<a style= 'text-decoration: none;' href='adicionales/logout.php'>Volver</a>";
	if ( !isset($_SESSION["usuario"]) || $_SESSION["usuario"] == "" ) {
		echo "<br><br><div style='width: 250px; padding: 20px; margin: 0 auto; text-align: center; border: 2px solid #2471A3; font-size: 1em; font-family: lucida console;'>Debe de iniciar sesión<br><br>" . $return ."</div>";
		die();
	} else {
		$varsesionUsuario = $_SESSION["usuario"];
	}
?>