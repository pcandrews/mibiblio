<?php
	
	/** 
	*	Dependecias: 
	*		Ninguna.
	*
	*	Descripcion: 
	*/

	ini_set("display_errors", "On");
	error_reporting(E_ALL | E_STRICT);

 	header("Content-Type: text/html; charset=UTF-8");

	require_once(LIB_PATH.DS."config/config.php");

	class Arreglo {


		/**
		*	Devuelve un array en un string separado por comas.
		*/
		public static function arreglo_a_string ($a) {
			$separado_por_comas = implode(",", $a);
			return $separado_por_comas; 
		}


		/**
		*	Notas: == compares the values of variables for equality, type casting as necessary.
		*	=== checks if the two variables are of the same type AND have the same value.
		*/
		public static function comparar_arreglos ($a, $b) {
			$control = FALSE;
			if ( $a === $b ) {
			    echo 'We are the same!';
			    $control = TRUE;

			   
			}
			else {
				//echo "No son iguales";
				//echo "<br />";
			}

			/*
			echo "<br />";
			for($i=0; $i<count($a); $i++) {
				echo "Arreglo $a: ".$a;
				echo "<br />";
			}

			echo "<br />";
			for($i=0; $i<count($b); $i++) {
				echo "Arreglo $b: ".$b;
				echo "<br />";
			}

			echo "Arreglo $a: ";
			echo "<br />";
			print_r($a);
			echo "<br />";
			echo "<br />";

			echo "Arreglo $b: ";
			echo "<br />";
			print_r($b);
			echo "<br />";
			echo "<br />";
			*/

			return $control;
		}

	}

?>