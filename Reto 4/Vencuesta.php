<?php 
	require_once('adicionales/controlSesion.php');
	require_once('adicionales/controlInvitados.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Vencuesta.php</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans|Poiret+One" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/VencuestaStyle.css">
</head>
<body>

	<?php
		$encuestaErr = "";
		echo $varsesionId;
		if ( isset($_POST["votar"]) ) {
			if ( isset( $_POST["encuestaRadio"] ) ) {
				$encuestaRadio = $_POST["encuestaRadio"];


				require_once("funciones/conexionMysql.php");
				$query = mysqli_query($conn, "SELECT * FROM encuesta WHERE id='$varsesionId'");
				$numrows = mysqli_num_rows($query);
				if ( $numrows == 0 ) {
					mysqli_query($conn, "INSERT INTO encuesta VALUES('$varsesionId', '$encuestaRadio')");
					header("Location: encuestaresultados.php");
				} else {
					$encuestaErr = "Usted ya ha votado";
					mysqli_close($conn);
				}
			} else {
				$encuestaErr = "Tiene que elegir una opción";
			}
		}
	?>

	<br>
	<form id="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">
		<h1>Encuesta</h1>
		<br>
		<p>¿Cree ud. que el precio de la vivienda seguirá subiendo al ritmo actual?</p>
		<span><?php echo $encuestaErr; ?></span>
		<br>
		<input type="radio" name="encuestaRadio" value="si"> Si
		<br>
		<input type="radio" name="encuestaRadio" value="no"> No
		<br><br>
		<input id="submitButton" type="submit" name="votar" value="votar">
		<br><br>
		<a href="encuestaresultados.php"><i class="fas fa-poll"></i> Ver resultados</i></a>
		<br><br><hr><br>
		<div id="identificationDiv">
			<p>Usted se ha identificado como <span><?php echo $_SESSION["usuario"]; ?></span> <a href='adicionales/logout.php'>(Salir)</a></p>
			<a href="Vprincipal.php"><i class="fas fa-arrow-circle-left"></i> Volver al menú principal</a>
		</div>
	</form>
</body>
</html>