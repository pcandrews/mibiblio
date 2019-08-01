<pre>
<?php

	header('Content-Type: text/html; charset=UTF-8'); 
	ini_set("display_errors", "On");
	error_reporting(E_ALL | E_STRICT);
 	header("Content-Type: text/html; charset=UTF-8");
 	date_default_timezone_set('America/Argentina/Tucuman');
 	setlocale(LC_ALL, 'es-AR');

	require_once("config/config.php");

	function enviar_y_mostrar_error() {
		echo "<br />";
		echo "<br />";
		$error = log::leer_ultimas_n_lineas(1,LOG_ERRORS);
		echo $error;
		Email::gmail($error);
	}
?>
</pre>