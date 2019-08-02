<?php
	
	header('Content-Type: text/html; charset=UTF-8');
	ini_set("display_errors", "On");
	error_reporting(E_ALL | E_STRICT);
	header("Content-Type: text/html; charset=UTF-8");
	date_default_timezone_set('America/Argentina/Tucuman');
	setlocale(LC_ALL, 'es-AR');
 	
	// Dependecias
	require_once(__DIR__."/../cfg/config_milib.php");

	class Log {

		/* 
			Lee las n ultimas lineas del log de dia actual.
		*/
		public static function leer_ultimas_n_lineas($n=1) {
			$filas = file(LOG_PATH . "/log_errores-".date("Y-m-d").".log");
			$ultimaLinea = $filas[count($filas)-$n];
			return $ultimaLinea;
		}

		public static function guardar($error) {
			error_log(date("Y/m/d-G:i")." - {$error}\n", 3, LOG_PATH . "/log_errores-".date("Y-m-d").".log");
		}
	}
?>

