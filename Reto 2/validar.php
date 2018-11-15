<html>
  <title>validar.php</title>
  <meta charset="UTF-8">
<?php
  /* Abre el documento. Si no se puede abrir, mensaje de error */
  /* Lectura y escritura. Cua escribe, no borra nada y escribe al final del documento. */
  $fopen = fopen("listaAlumnos.txt", "a+") or die("No se puede abrir el archivo");

  /*Si el boton submit pulsado es el de registrar el alumno*/
  if (isset($_POST["registrar"])) {
    /* Declaro las variables $_POST */
    $name = ucfirst($_POST["name"]); /* ------> ucfirst = primera letra en mayúscula */
    $telephone = $_POST["telephone"];
    $return = "<br><a href='alumno.html'>Volver al formulario</a>"; /* ---> Variable que aparece despues de cada error para volver al formulario */
    
    /* Validacion de los campos obligatorios con la funcion empty() */

    /* Si tanto el telefono como el nombre estan vacios */
      if (empty($name) && empty($telephone)) {
        echo "Tiene que rellenar el campo del nombre y del teléfono.";
        echo $return;

    /* Si el nombre contiene numeros (incluso si tuviera numeros y letras). Se aplica tanto a numeros como a string de numeros ---> error */
    } elseif (is_numeric($name)) {
        echo "El nombre no puede incluir números";
        echo $return;

    /* Si el nombre esta vacio */
    } elseif (empty($name)) {
        echo "No puede dejar el campo del nombre sin rellenar.";
        echo $return;

    /* Si el telefono esta vacio */
    } elseif (empty($telephone)) {
        echo "No puede dejar el campo del télefono sin rellenar.";
        echo $return;

    /* Si el telefono contiene caracteres alfabeticos ---> error */
    } elseif (!is_numeric($telephone)) {
        echo "En el campo 'Teléfono' únicamente puede escribir carácteres numéricos.";
        echo $return;

    /* OPCIONAL - Si la longitud del telefono es diferente de 9 ---> error */
    } elseif (strlen($telephone) != 9) {
        echo "El número de teléfono tiene que estar formado por 9 números.";
        echo $return;

    /* Si tanto el campo nombre, como el de telefono estan bien rellenados... */
    } else {
      /* Si el checkbox no esta checkeado... */
      if (!isset($_POST["enrolled"])) {
        echo "el alumno $name con el teléfono $telephone no está matriculado.";
      /* Si el checkbox esta checkeado... */
      } else {
        /* crear una variable para que guarde esa string */
        $enrolled = "está matriculado en "; 
        /* Si NO hay algun curso marcado... */    
        if (!isset($_POST["course"])) {
        /* ¿Por que declarar la variable course aqui y no al principio del codigo? 
          El course solo sera seleccionado una vez el alumno sea matriculado. Mientras no se matricule, 
          no escogemos ningun grado, luego no nos devuelve nada (o undefined/NULL). Para hacer esta comparacion
          necesitamos un isset (el que esta justo encima)*/
        $course = "Tiene que elegir un curso.";
        echo $course;
        echo $return;
        } else {
          $course = $_POST["course"];
          /* OPCIONAL - [Coreccion de sintaxis] Para el mensaje final, dependiendo del curso elegido */
          if ($course == "secundaria" ) {
            $course = "educación secundaria";
          } elseif ($course == "gradoMedio" or $course == "gradoSuperior") {
            /* substr ---> devuelve partes de una cadena */
            /* substr(string/variable, posicion en la que empiezo, cantidad de caracteres) */
            /* lcfirst ---> primera letra en minuscula*/
            $course = "un ".substr($course, 0, 5)." ".lcfirst(substr($course, 5));
          }

          /* Por que isset aqui? Si no elijo ninguna actividad extraescolar, no defino la variable extraActivites(el post de las actividades extraescolares) */      
          if (!isset($_POST["extraActivities"])) {
            $extraActivities = "no está matriculado en ninguna actividad extraescolar.";
            /* El mensaje final si todo esta correcto */
            $datos = "El alumno $name con el teléfono $telephone, $enrolled $course y $extraActivities";
            /* El mensaje que guardo para escribir en el archivo .txt */
            $datosDos = "Nombre: ".$name.". Teléfono: ".$telephone.". Curso: ".$course.". Actividad/es extraescolar/es: ninguna";
            echo $datos;
            echo $return;
            fwrite($fopen, $datosDos.".\n\n");
            fclose($fopen);
          } else {
            /* El mensaje final si todo esta correcto */
            $datos = "El alumno $name con el teléfono $telephone, $enrolled $course y está matriculado en:<br><br>";
            /* El mensaje que guardo para escribir en el archivo.txt */
            $datosDos="Nombre: ".$name.". Teléfono: ".$telephone.". Curso: ".$course.". Actividad/es extraescolar/es:";
            foreach ($_POST["extraActivities"] as $eachExtraActivities) {
            /* foreach = para cada uno ---> ejecuta todo lo de aqui dentro por cada actividad extraescolar que recibe. */
            $datosDos = $datosDos.ucfirst($eachExtraActivities).", "; 
            $datos = $datos." "."<li>".ucfirst($eachExtraActivities)."</li>";          
            }
            echo $datos;
            echo $return;
            $datosDos = rtrim($datosDos , ", "); /* OPCIONAL rtrim ---> elimina los espacios en blanco (u otros caracteres que podemos especificar en el segundo parametro de la funcion) del final de un string */
            fwrite($fopen, $datosDos.".\n\n");
            fclose($fopen);
          }
        }  
      }
    }
  } elseif (isset($_POST["mostrar"])) /* ---> Si el boton pulsado es el de mostrar todos los alumnos */ { 
    $count = 0; /* OPCIONAL sirve para contar los alumnos (las lineas de texto) */  
    while(!feof($fopen)) /* Comprueba si el puntero a un archivo esta al final de del archivo */ {
      fgets($fopen); /* --->fgets: lee la primera linea del archivo */
      $count++; /* OPCIONAL */
    }
    $count = ($count-1)/2; /* -1 porque la linea final de documento es el recuento de los alumnos, no un alumno. El dividido entre dos, es porque entre cada alumno hay dos saltos de linea de vez de uno(\n\n) */
    echo nl2br(file_get_contents("listaAlumnos.txt")); /* nl2br ---> hace que se respeten los saltos de linea especificados en el documento de texto (\n) */
    /* file_get_content ---> convierte el fichero completo en una cadena (no respeta los saltos de linea) */
    echo "<br>Total de alumnos: $count"; /* OPCIONAL */
    
  } elseif (isset($_POST["estadisticas"])) {
      $fopen = fopen("listaAlumnos.txt", "r");
      $deporte = 0;
      $idioma = 0;
      $musica = 0;
      $lectura = 0;
      while(!feof($fopen)) {
        $line = fgets($fopen); 
        $deporte += substr_count($line, "Deporte"); /* ---> substr_count: cuenta el numero de apariciones del substring */
        $idioma += substr_count($line, "Idioma");
        $musica += substr_count($line, "Musica");
        $lectura += substr_count($line, "Lectura");
      }
    fclose($fopen);
    echo "Cantidad de alumnos por actividad extraescolar:";
    echo "<br>";
    echo "<li>Deporte: ".$deporte.".</li>"."<li>Idioma: ".$idioma.".</li>"."<li>Musica: ".$musica.".</li>"."<li>Lectura: ".$lectura.".</li>";
  }
 
  
    
?>
</html>