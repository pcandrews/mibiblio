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

		public static function guardar($error) {
			error_log(date("d/m/Y-G:i") . " - ERROR: " . $error . "  \n", 3, LOG_PATH . "/errores.log");
		}

		//define function name  
		public static function m_log($arMsg) {  
			//define empty string                                 
			$stEntry="";  
			//get the event occur date time,when it will happened  
			$arLogData['event_datetime']='['.date('D Y-m-d h:i:s A').'] [client '.$_SERVER['REMOTE_ADDR'].']';  
			//if message is array type  
			if(is_array($arMsg))  {  
				//concatenate msg with datetime  
				foreach($arMsg as $msg)  
				$stEntry.=$arLogData['event_datetime']." ".$msg."rn";  
			}  
			else {   //concatenate msg with datetime  
				$stEntry.=$arLogData['event_datetime']." ".$arMsg."rn";  
			}  
			//create file with current date name  
			$stCurLogFileName='log_'.date('Ymd').'.txt';  
			//open the file append mode,dats the log file will create day wise  
			$fHandler=fopen(LOG_PATH.$stCurLogFileName,'a+');  
			//write the info into the file  
			fwrite($fHandler,$stEntry);  
			//close handler  
			fclose($fHandler);  
		}  
	}

	//how to call function: 
//m_log("Sorry,returned the following ERROR: ".$fault->faultcode."-".$fault->faultstring); 

?>

