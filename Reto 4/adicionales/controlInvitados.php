<?php
	$returnMenu =  "<a style= 'text-decoration: none;' href='Vprincipal.php'>Volver al menú principal</a>"; // ---> Volver al menú principal
	$returnLogin = "<a style= 'text-decoration: none;' href='adicionales/logout.php'>Iniciar Sesión</a>"; // ---> Volver al inicio de sesión
	if ( $_SESSION["usuario"] == "Invitado" ) /* ---> Si el usuario es invitado */ {
		echo "<br><br><div style='width: 250px; padding: 20px; margin: 0 auto; text-align: center; border: 2px solid #2471A3; font-size: 1em; font-family: lucida console;'>No puede acceder a esta página como invitado.<br><br><br>" . $returnMenu . "<br><br>" . $returnLogin . "</div>";
		die();
	}
?>