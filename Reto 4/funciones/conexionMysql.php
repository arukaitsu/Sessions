<?php

	$conn = mysqli_connect("localhost", "root", "", "viviendas");
	mysqli_set_charset($conn, "utf8");
	if ($conn) {
    	true;
	} else {
		die("Connection failed: " . mysqli_connect_error());
	}

?>