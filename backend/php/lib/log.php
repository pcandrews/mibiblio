<?php
	
	header('Content-Type: text/html; charset=UTF-8');
	ini_set("display_errors", "On");
	error_reporting(E_ALL | E_STRICT);
	header("Content-Type: text/html; charset=UTF-8");
	date_default_timezone_set('America/Argentina/Tucuman');
	setlocale(LC_ALL, 'es-AR');
 	
	// Dependecias
	require_once(__DIR__."/../cfg/config_mibiblio.php");

	class Log {

		public static function leer_ultimas_n_lineas($n=1,$archivo) {
			$filas = file($archivo,FILE_USE_INCLUDE_PATH);
			$ultimaLinea = $filas[count($filas)-$n];
			return $ultimaLinea;
		}

		public static function log_error($error) {
			error_log(date("d/m/Y-G:i") . " - ERROR: " . $error . "  \n", 3, LOG_ERRORES_PATH . "/logErrores.txt");
			// seguri desde aqui para hacer la funcion esta
			
		}
	}

?>