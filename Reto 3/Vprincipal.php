<?php 
  require_once('adicionales/controlSesion.php');
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Vregistro.php</title>
    <meta charset="UTF-8">
<!--     <link href="https://fonts.googleapis.com/css?family=Open+Sans|Poiret+One" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans|Poiret+One" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/VprincipalStyle.css">
  </head>
  <body>
    <br>
    <div id="menu">
      <h1>BASE DE DATOS PRINCIPAL</h1>
      <br><br>
      <div><a href="VListado.php">Consultar viviendas<span><i class="fas fa-search"></i></span></a></div>
      <br>
      <div><a href="Vencuesta.php">Encuesta <span><i class="fas fa-poll"></i></span></a></div>
      <br>
      <div><a href="VInsertar.php">Insertar <span><i class="fas fa-pen"></i></span></a></div>
      <br>
      <div><a href="Vborrar.php">Eliminar viviendas <span><i class="fas fa-trash-alt"></i></span></a></div>
      <br><br>
      <p id="identification">Usted se ha identificado como <span><?php echo $_SESSION["usuario"]; ?> <a href="adicionales/logout.php"></span>(Salir)</a></p>
    </div>

  </body>
</html>