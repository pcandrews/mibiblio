<pre>

<?php

	/** 
	*	Dependecias: 
	*		iniciar_base_de_datos.php
	*
	*	Descripcion:
	*		Inicia el sistema desde cero.
	*		El primer archivo que se debe lanzar para instalar el sistema.
	*/

	header('Content-Type: text/html; charset=UTF-8'); 
	ini_set("display_errors", "On");
	error_reporting(E_ALL | E_STRICT);
	header("Content-Type: text/html; charset=UTF-8");
	date_default_timezone_set('America/Argentina/Tucuman');
	setlocale(LC_ALL, 'es-AR');


	require_once("config/config.php");


	/*
	$bd3 = new Ikon(DB_IKON);

	$nombreArchivoXML = "epgMuyCorto.xml";
	$nombreTabla = TABLA_EPG_DB_IKON;
	$registros = $bd3->registrosEPG;
	
	$bd3->epg_xml_a_mysql($nombreArchivoXML, $nombreTabla, $registros);

	$nombreArchivoCSV = "extraEPG.csv";
	$nombreTabla = TABLA_EXTRA_EPG_DB_IKON;
	$registros = $bd3->registrosExtraEPG;

	$bd3->csv_a_mysql($nombreArchivoCSV, $nombreTabla, $registros);
	*/

	$m = new MapaAbonados(DB_MAPA_ABONADOS);
	
	$lonlat = $m->obtener_coord_gps_tabla();


	for($i=0; $i<count($lonlat); $i++) {
		echo "lon: " . $lonlat[$i]["lon"];
		echo "<br />";
		echo "lat: " . $lonlat[$i]["lat"];
		echo "<br />";
    }
    



	echo "<br />";
	echo "<br />";
	echo "<br />";
	echo "<br />";
	echo "FIN";

?>

</pre>