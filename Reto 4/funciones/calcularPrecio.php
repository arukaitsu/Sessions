<?php
	function calcularPrecio($metros_cuadrados, $categoria) {
		
		if ( $categoria == "costas" ) {
			$precioCategoria = 4000;
		} elseif ( $categoria == "promociones" ) {
			$precioCategoria = 2500;
		} else if ( $categoria == "ofertas" ) {
			$precioCategoria = 3000;
		}

		$numeroPrecio = $metros_cuadrados * $precioCategoria;
		return $numeroPrecio;

	}

?>