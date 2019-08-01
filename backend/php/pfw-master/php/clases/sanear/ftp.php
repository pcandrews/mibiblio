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

	class FTP {


		/**
		*	Conecta con $ftpUser@$ftpServer $ftpPass.
		*	Descarga FTP_SERVER_FILE.
		*	Lo guarda como FTP_LOCAL_FILE.
		*	Entradas: ninguna.
		*	Retorno: $control=TRUE, conexion y descarga exitosa.
		*/
		public static function conex_y_descarga ($ftpServer, $ftpUser, $ftpPass, $ftpLocalFile, $ftpSeverFile) {
			$conex = ftp_connect($ftpServer);
			$control = FALSE;
			$login_result = ftp_login($conex, $ftpUser, $ftpPass);
			if($login_result) {
				if (ftp_get($conex, $ftpLocalFile, $ftpSeverFile, FTP_BINARY)) {
				    $control = TRUE;
				} 
				else {
					error_log(date("d/m/Y-G:i") . " - ERROR: Imposible descargar {$ftpSeverFile} desde el servidor {$ftpServer}.\n", 3, LOG_ERRORS);
					$control = FALSE;
				}
			}
			else {
				error_log(date("d/m/Y-G:i") . " - ERROR: Imposible establer conexion con el el servidor " . FTP_SERVER . ".\n", 3, LOG_ERRORS);
				$control = FALSE;
			}
			ftp_close($conex);
			return $control;
		}
	}
?>