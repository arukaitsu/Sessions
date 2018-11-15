<html>
  <title>validar.php</title>
  <meta charset="UTF-8">
<?php
  /*Declare variables*/
  $return = "<br><a href='alumno.html'>Volver al formulario</a>";
  $name = ucfirst($_POST["name"]);
  $telephone = $_POST["telephone"];  
  if (isset($_POST["extraActivities"])) {
    $extraActivities = $_POST["extraActivities"];    
  } else {
    null;
  };

  /* Compulsory fields */
   if (empty($name) && empty($telephone)) {
    echo "Tiene que rellenar el campo del nombre y del teléfono.";
    echo $return;
     
  } elseif (is_numeric($name)) {
    echo "El nombre no puede incluir números";
    echo $return;
     
  } elseif (empty($name)) {
    echo "No puede dejar el campo del nombre sin rellenar.";
    echo $return;
     
  } elseif (empty($telephone)) {
    echo "No puede dejar el campo del télefono sin rellenar.";
    echo $return;
    
  } elseif (!is_numeric($telephone)) {
    echo "En el campo 'Teléfono' únicamente puede escribir carácteres numéricos.";
    echo $return;
    
  } elseif (strlen($telephone) != 9) {
    echo "El número de teléfono tiene que estar formado por 9 números.";
    echo $return;
    
  } else {
    if (!isset($_POST["enrolled"])) {
      echo "el alumno $name con el teléfono $telephone no está matriculado.";
    } else {
      $enrolled = "está matriculado en ";     
      if (!isset($_POST["course"])) {
      echo "Tiene que elegir un curso";
      echo $return;
      } else {
      $course = $_POST["course"];
      /* [SYNTAX CORRECTION] Depending of the choosen course */
        if ($course == "secundaria" ) {
        $course = "educación secundaria";
        } elseif ($course == "gradoMedio" or $course == "gradoSuperior") {
        $course = "un ".substr($course, 0, 5)." ".lcfirst(substr($course, 5));
        }
      echo "El alumno $name con el teléfono $telephone, $enrolled $course y la actividad extraescolar seleccionada es $extraActivities.";
      }
    }
  }
?>
</html>