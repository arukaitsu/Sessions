<?php

	session_start();
	$varsesion = $_SESSION["usuario"];
	$deleteLog = $_SESSION["deleteVivienda"];
	$return = "<a href='adicionales/logout.php'></a>";
	if ( $varsesion == null || $varsesion == "" ) {
		echo "Usted no tiene autorización;";
		echo $return;
		die();
	}
?>

<style>
<?php include '../css/viviendaEliminadaStyle.css'; ?>
</style>

<?php
	echo "<br><br>";
	echo $deleteLog;
	echo "<br>";
	echo "<div><a href='../Vborrar.php'>Volver a Eliminar Viviendas<a></div>";
	echo "<br>";
	echo "<div><a href='../Vprincipal.php'>Volver al menu principal<a></div>";
	$deleteLog = "";
?>