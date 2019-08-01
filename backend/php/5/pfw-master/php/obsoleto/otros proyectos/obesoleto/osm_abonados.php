<pre>

<?php
	
	/** 
	*	Dependecias: 
	*		config.h.
	*	
	*	Descripcion: 
	*		Clase para manejo de Mysql.
	*/

	header('Content-Type: text/html; charset=UTF-8'); 
	ini_set("display_errors", "On");
	error_reporting(E_ALL | E_STRICT);
 	header("Content-Type: text/html; charset=UTF-8");
 	date_default_timezone_set('America/Argentina/Tucuman');
 	setlocale(LC_ALL, 'es-AR');

	require_once("config/config.php");

	$m = new MapaAbonados(DB_MAPA_ABONADOS);

	$m->close_connection();

	
	echo "<br />";
	echo "<br />";
	echo "<br />";
	echo "<br />";
	echo "FIN";

?>

</pre>