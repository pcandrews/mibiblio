<?php
	
	ini_set("display_errors", "On");
	error_reporting(E_ALL | E_STRICT);

 	header("Content-Type: text/html; charset=UTF-8"); 

	require_once("config.php");
	require_once("database.php");
	require_once("funciones_auxiliares.php");
	
	/*
	1. Conecta con FTP_SERVER.
	2. Descarga FTP_SERVER_FILE y lo guarda en FTP_LOCAL_FILE.
	
	Entradas: ninguna.
	
	Retorno: $control=TRUE, conexion y descarga exitosa.
	*/
	function conexFTP () {
		$conex = ftp_connect(FTP_SERVER);
		$control = FALSE;

		// iniciar sesión con nombre de usuario y contraseña
		$login_result = ftp_login($conex, FTP_USER_NAME, FTP_USER_PASS);

		if($login_result) {
			//echo "1. Conexion establecida.";
			//echo "<br />";
		
			// intenta descargar FTP_SERVER_FILE y guardarlo en FTP_NEW_LOCAL_FILE
			if (ftp_get($conex, FTP_NEW_LOCAL_FILE, FTP_SERVER_FILE, FTP_BINARY)) {
			    //echo "2. Se ha guardado satisfactoriamente en " . FTP_NEW_LOCAL_FILE . ".";
			    //echo "<br />";
			    $control = TRUE;
			} 
			else {
			    //echo "Error al intentar descargar el archivo EPG.XML desde el serviro FTP.";
				error_log(date("d/m/Y-G:i") . "-ERROR: Imposible descargar '" . FTP_SERVER_FILE . "'' desde el servidor " . FTP_SERVER . ".\n", 3, LOG_ERRORS);
				$control = FALSE;
				//mail
			}
		}
		else {
			//echo "Error al intentar establer la conexion con el FTP.";
			error_log(date("d/m/Y-G:i") . "-ERROR: Imposible establer conexion con el el servidor " . FTP_SERVER . ".\n", 3, LOG_ERRORS);
			$control = FALSE;
			//mail
		}

		// cerrar la conexión ftp
		ftp_close($conex);

		return $control;
	}

	/*
	Compara que FTP_NEW_LOCAL_FILE y FTP_LOCAL_FILE sean distintos

	Entradas: ninguna.

	Salidas:control=TRUE, si los archivos son diferentes

	Notas:
	*/
	function sonArchivosDistintos () {
		$control = FALSE;

		if (md5_file(FTP_LOCAL_FILE) != md5_file(FTP_NEW_LOCAL_FILE)) {
			$control = TRUE;
		}
		else {
			error_log(date("d/m/Y-G:i") . "-ATENCION: Los archivos '" .FTP_NEW_LOCAL_FILE . "'' y '" . FTP_LOCAL_FILE . "'' son igualess.\n", 3, LOG_ERRORS);
			$control = FALSE;
		}

		return $control;
	}


	/*
	3. Compara si FTP_NEW_LOCAL_FILE y FTP_LOCAL_FILE sean distintos

	Entradas: $archA y $archB, los archivos a comparar, por defecto FTP_NEW_LOCAL_FILE y FTP_LOCAL_FILE

	Salidas:control=TRUE, si $archB es mas nuevo y viceversa. 

	Notas:---.
	*/
	function encontrarArchivoMasNuevo ($archA=FTP_LOCAL_FILE,$archB=FTP_NEW_LOCAL_FILE) {
		$control = FALSE;

		if (filemtime($archA) < filemtime($archB)) {
			$control = TRUE;
		}
		else {
			error_log(date("d/m/Y-G:i") . "-ATENCION: Los archivos '" .FTP_NEW_LOCAL_FILE . "'' y '" . FTP_LOCAL_FILE . "'' son igualess.\n", 3, LOG_ERRORS);
			$control = FALSE;
		}

		return $control;
	}

	/*
	4. Descomprime el archivo FTP_LOCAL_FILE.
	
	Entradas: ninguna.
	
	Retorno: $control=TRUE, descompresión exitosa. 
	
	Notas: system() — Ejecuta un programa externo y muestra su salida.
	exec() - Ejecuta un programa externo. 
	*/
	function descomprimirEPG () {
		$comando = "unrar e ". FTP_LOCAL_FILE;
		//$ultimaLinea = system( $comando, $retval);
		$ultimaLinea = exec( $comando, $retval);
		$control = FALSE;

		if ($ultimaLinea){
			$control = TRUE;
		}
		else {
			error_log(date("d/m/Y-G:i") . "-ERROR: Imposible extraccion del contenido de '".FTP_NEW_LOCAL_FILE."'.\n", 3, LOG_ERRORS);
			$control = FALSE;
		}

		return $control;
	}

	/*
	5. Controla que en archivo XML sea valido. 

	Entradas: ninguna.

	Retorno: $control=TRUE, si el archivo XML es valido.
	*/
	function XMLvalido () {
		$prev = libxml_use_internal_errors(true);
		$control = true;
		
		try {
			new SimpleXMLElement(EPG_XML, 0, true);
		} 
		catch(Exception $e) {
			$control = FALSE;
		}
		if(count(libxml_get_errors()) > 0) {
			// There has been XML errors
			$control = FALSE;
		}
		else {
			$control = TRUE;
		}
		
		// Tidy up.
		libxml_clear_errors();
		libxml_use_internal_errors($prev);

		return $control;
	}


	/*
	6. Llena 

	Entradas:

	Retorno:

	Notas: Con fines de simplicidad y homegeneidad de diseño. MVC.
	*/
	function llenarEPG () {
		return EPG::fill_table();
	}


	
	/*
	7. Busca una duracion dada en ikon.EPG

	Entradas: Duracion a buscar.

	Retorno: $control=TRUE, si encuentra un valor con la duracion pasada.
	*/
	function buscarDuracion ($duracion="00:00:00",&$info) {
		$info = EPG::search_by_duration($duracion);

		if (empty($info)){
			$control = FALSE;
		}
		else {
			$control = TRUE;
			error_log(date("d/m/Y-G:i") . "-ERROR: Encontrados programas con duracion '00:00'.\n", 3, LOG_ERRORS);
		}

		return $control;
	}

	/*
	Lista toda la informacion sobre el asunto requerido.

	Redundante
	*/
	/*
	function listarInfoSinRepetir ($valor,$tabla) {
		$info = array ();
		$info = EPG::get_info_no_repeat($valor,$tabla);
		return $info;
	}
	*/


	/*
	8. Compara las listas de canales de EPG y Extra_EPG

	Entradas:ninguna.

	Salidas:$dill=diferencia entre arrays
	*/
	function compararListasCanales () {
		$canEPG = array();
		$canExtra = array();

		$canEPG = EPG::get_info_no_repeat(CANALES,"EPG");
		$canExtra = EPG::get_info_no_repeat(CANALES,"Extra_EPG");

		$sonIguales = ($canEPG === $canExtra);

		if($sonIguales) {
			$control = FALSE;
		}
		else {
			$control = TRUE;
			error_log(date("d/m/Y-G:i") . "-ERROR: Listas de canales son diferentes en EPX y Extra_EPG.\n", 3, LOG_ERRORS);
			formatearInfoEPG($canEPG);
			formatearInfoEPG($canExtra);
		}

		return $control;
	}

	/* Fin Funciones EPG */

	 

?>