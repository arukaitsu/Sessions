<!DOCTYPE HTML> 
<html lang="en">
  <head>
    <title>Vregistro.php</title>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans|Poiret+One" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/VregistroStyle.css">
  </head>
  <body>

     <?php     

      $loginErr = $nombreErr = $claveErr = $confirmarClaveErr = "";
      $login = $nombre = $clave = $confirmarClave = "";
      
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        if ( empty($_POST["login"]) ) { 
          $loginErr = "Tiene que escribir un nombre de login";
        } else {
          $login = test_input($_POST["login"]);
        } 

        if ( empty($_POST["nombre"]) ) { 
          $nombreErr = "Tiene que escribir un nombre";
        } else {
          $nombre = test_input($_POST["nombre"]);
        } 

        if ( empty($_POST["clave"]) ) { 
          $claveErr = "Tiene que escribir una clave";
        } elseif ( strlen($_POST["clave"]) < 8 || strlen($_POST["clave"]) > 16) {
          $claveErr = "La clave debe de tener entre 8 y 16 caracteres";
        } else {
          $clave = test_input($_POST["clave"]);
        }
 
        if ( empty($_POST["confirmarClave"]) ) { 
          $confirmarClaveErr = "Tiene que confirmar su nueva clave";
        } elseif ($_POST["clave"] != $_POST["confirmarClave"]) {
          $confirmarClaveErr = "Las claves no coinciden";
        } else {
          $confirmarClave = test_input($_POST["confirmarClave"]);
        }
      
        if (!empty($_POST["login"]) && !empty($_POST["nombre"]) && !empty($_POST["clave"]) && !empty($_POST["confirmarClave"]) && strlen($_POST["clave"]) >= 8 && strlen($_POST["clave"]) <= 16 && $_POST["clave"] == $_POST["confirmarClave"]) {
            $login = $_POST["login"];
            $nombre = $_POST["nombre"];
            $clave = md5($_POST["clave"]);

            // Conectarse a MySQL
            require_once("funciones/conexionMysql.php");

            // Realizar consultas
            $query = mysqli_query($conn, "SELECT * FROM usuarios WHERE login='$login'");

            $numrows=mysqli_num_rows($query);
            if ( $numrows==0 ) {
              // $clave = md5($clave); // Encriptar contraseñas
              $conn=mysqli_query($conn,"INSERT INTO usuarios (login, nombre, clave) values('$login', '$nombre', '$clave')");
              header("Location: adicionales/logout.php");
            } else {
              $loginErr = "Este nombre de login ya está registrado.";
            }
            mysqli_close($conn);
        }
      }

      function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }
    ?>

    <br>
    <form id="form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
      <h1>INSERTE LOS DATOS DEL NUEVO USUARIO</h1>
      <br><br>
      <label for="login">Login del nuevo usuario:</label>
      <span class="error"><?php echo $loginErr; ?></span>
      <br>
      <input type="text" name="login"> *
      <br><br>
      <label for="nombre">Nombre del nuevo usuario:</label>
      <span class="error"><?php echo $nombreErr; ?></span>
      <br>
      <input type="text" name="nombre"> *
      <br><br>
      <label for="clave">Clave del nuevo usuario:</label>
      <span class="error"><?php echo $claveErr; ?></span>
      <br>
      <input type="password" name="clave"> *
      <br><br>
      <label for="confirmarClave">Confirma la nueva clave:</label>
      <span class="error"><?php echo $confirmarClaveErr; ?></span>
      <br>
      <input type="password" name="confirmarClave"> *
      <br><br><br>
      <input id="submitButton" type="submit" name="crearUsuario" value="Crear usuario">
      <br><br>
      <a href="Viviendaszub.html">Cambiar de usuario (Volver al inicio de sesión)</a>
    </form>
  </body>
</html>