<?php 
	require_once('adicionales/controlSesion.php');
	require_once('adicionales/controlInvitados.php');
?>

<!DOCTYPE html>
<html>
<head>
	<title>encuestaresultados.php</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans|Poiret+One" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/encuestaresultadosStyle.css">
</head>
<body>

	<br>
	<div id="main">
	<div id="identificationDiv">
		<p>Usted se ha identificado como <span><?php echo $_SESSION["usuario"]; ?></span> <a href='adicionales/logout.php'>(Salir)</a></p>
		<a href="Vprincipal.php"><i class="fas fa-arrow-circle-left"></i> Volver al menú principal</a>
		<br>
		<a href="Vencuesta.php"><i class="fas fa-arrow-circle-left"></i> Volver a la encuesta</a>
	</div>
	<br><hr><br>
	<h1>Encuesta. Resultados de la votación</h1>
	<br>
	</div>

	<?php

		require_once("funciones/conexionMysql.php");

		$querySum = mysqli_query($conn, "SELECT count(voto) as totalVotos FROM encuesta");
		while($rowSum = mysqli_fetch_assoc($querySum)) {
			$totalVotos = $rowSum['totalVotos'];
		}


		$queryCount = mysqli_query($conn, "SELECT voto, count(*) as parcialVotos FROM encuesta GROUP BY voto");

		$numrows = mysqli_num_rows($queryCount);
		if ( $numrows == 0 ) {
			echo "Aún no ha votado nadie";
			echo $varsesionId;

		} else {
			echo "<table><tr><th>Respuesta</th><th>Votos</th><th>Porcentaje</th></tr>";
			while($row = mysqli_fetch_assoc($queryCount)) { 
				echo "<tr><td>" . $row['voto'] . "</td><td>" . $row['parcialVotos'] . "</td><td>" . round($row['parcialVotos']*100/$totalVotos, 2) . "%" ."</td></tr>";
			}

			echo "</table><br><div id='countVotes'><p>Total de votos: " . $totalVotos . "</p></div>";
		}
		mysqli_close($conn);
	?>


		

</body>
</html>