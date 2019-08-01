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

	
	$ini = new MakeMysqlDatabase();
	$bd = new Ikon(DB_IKON);
	$m = new MapaAbonados(DB_MAPA_ABONADOS);

	$ini->resetear_bd_y_usuerio();
	
	$bd->cargar_bd_extra_epg(); 
	$bd->cargar_bd_epg();
	$bd->close_connection();

	$m->cargar_tabla_abonados();
	$m->close_connection();



	/***********************************************/
	/*				  obsoleto 					   */
	/***********************************************/

	/**
	*
	*/
	function resetear_bd_y_usuerio() {
		$bd = new MakeMysqlDatabase();
		//1. Se conecta como root por defecto
		//2. Se elimina la base de datos anterior.
		$bd->eliminar(DB_IKON);
		$bd->eliminar(DB_MAPA_ABONADOS);
		//3. Se crea el usuario
		$bd->crear_usuario();
		//4. Crea base de datos
		$bd->crear_bd(DB_IKON);
		$bd->crear_bd(DB_MAPA_ABONADOS);
		//5. Cierra la conexion con el root
		$bd->cerrar_conexion();
		//6. Se conecta con el usuario creado
		$bd->conectar(DB_SERVER, DB_USER, DB_PASS, DB_IKON);
		//7. Se crean las tablas.
		$nombreTablas = array(TABLA_EPG_DB_IKON,TABLA_EXTRA_EPG_DB_IKON);
	 	$registrosTabla = array (RG_EPG,RG_EXTRA_EPG);
		$bd->crear_tablas($nombreTablas, $registrosTabla);
		//8. Se cierra la conexion.
		$bd->cerrar_conexion();
		// Se repiten los pasos 6,7 y 8.
		$bd->conectar(DB_SERVER, DB_USER, DB_PASS, DB_MAPA_ABONADOS);
		$nombreTablas = array(TABLA_ABONADOS_DB_MAPA_ABONADOS);
	 	$registrosTabla = array (RG_ABONADOS);
		$bd->crear_tablas($nombreTablas, $registrosTabla);
		$bd->cerrar_conexion();
	}
	

	/**
	*
	*/
	function cargar_bd_extra_epg () {
		// Se carga Extra_EPG
		echo "<br />";
		echo "<br />";
		$bd3 = new Ikon(DB_IKON);
		$nombreArchivoCSV = DATA_PATH.DS."extraEPG.csv";
		$nombreTabla = TABLA_EXTRA_EPG_DB_IKON;
		$registros = $bd3->registrosExtraEPG;
		$bd3->csv_a_mysql($nombreArchivoCSV, $nombreTabla, $registros);
		$bd3->close_connection();
	}

	echo "<br />";
	echo "<br />";
	echo "<br />";
	echo "<br />";
	echo "FIN";
	
?>

</pre>