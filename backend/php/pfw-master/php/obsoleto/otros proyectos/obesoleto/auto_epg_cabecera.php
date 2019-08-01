<pre>

<?php

	header('Content-Type: text/html; charset=UTF-8'); 
	ini_set("display_errors", "On");
	error_reporting(E_ALL | E_STRICT);
 	header("Content-Type: text/html; charset=UTF-8");
 	date_default_timezone_set('America/Argentina/Tucuman');
 	setlocale(LC_ALL, 'es-AR');

	require_once("config/config.php");


	control_epg_cabecera();

	/**
	*	
	*/
	function control_epg_cabecera() {

		$ik = new Ikon(DB_IKON);

		if (FTP::conex_y_descarga (FTP_SERVER, FTP_USER_NAME, FTP_USER_PASS, FTP_NEW_LOCAL_FILE, FTP_SERVER_FILE) ) {
			echo "01. Conexion FTP: OK.";
			echo "<br />";
			echo "02. Descarga FTP: OK.";	
			echo "<br />";
			echo "03. Archivo FTP guardado como '" . FTP_NEW_LOCAL_FILE . "': OK.";
			echo "<br />";
			
			//if(Archivo::son_archivos_distintos (FTP_LOCAL_FILE, FTP_NEW_LOCAL_FILE)) { <- debe ir este
			if(!Archivo::son_archivos_distintos(FTP_LOCAL_FILE, FTP_NEW_LOCAL_FILE)) {
				echo "04. " . FTP_LOCAL_FILE . " y " . FTP_NEW_LOCAL_FILE . " diferentes: OK.";
				echo "<br />";	

				if(Archivo::encontrar_archivo_mas_nuevo (FTP_LOCAL_FILE, FTP_NEW_LOCAL_FILE)) {
					echo "05. Nuevo '" . FTP_NEW_LOCAL_FILE . "': OK.";
					echo "<br />";	

					unlink(FTP_LOCAL_FILE); //Elimina FTP_LOCAL_FILE
					rename(FTP_NEW_LOCAL_FILE,FTP_LOCAL_FILE);

					if(Archivo::descomprimir_RAR(FTP_LOCAL_FILE, DATA_PATH)) {
						echo "06. Extraccion de '" . FTP_LOCAL_FILE . "': OK.";
						echo "<br />";	
						
						//echo EPG_XML;
						if(Archivo::XML_valido(EPG_XML)) {
							echo "07. Valicion XML : OK.";
							echo "<br />";

							//if($ik->epg_xml_a_mysql(DATA_PATH.DS."epg.xml", TABLA_EPG_DB_IKON, $ik->get_registrosEPG())) { <- debe ir este
							if($ik->epg_xml_a_mysql(DATA_PATH.DS."epgMuyCorto.xml", TABLA_EPG_DB_IKON, $ik->get_registrosEPG())) {
								echo "08. Carga de '" . TABLA_EPG_DB_IKON . "': OK.";
								echo "<br />";

								/*
								$info = $ik->buscar_por_duracion("00:00:00");

								echo "<br />";
								for($i=0; $i<count($info); $i++) {
									echo "Numero canal:".$info[$i]['numeroCanal']." - ";
									echo "Titulo programa: ".$info[$i]["tituloPrograma"]." - ";
									echo "Hora inicio: ".$info[$i]["horaInicioPrograma"]." - ";
									echo "Hora fin: ".$info[$i]["horaFinPrograma"]." - ";
									echo "Duracion: ".$info[$i]["duracionPrograma"];
									echo "<br />";
								}
								*/

								//if(!$ik->verificar_duracion("00:00:00")) { <- debe ir este
								if(!$ik->verificar_duracion("00:00:00")) {
									echo "09. No hay programas con duracion '00:00': OK.";
									echo "<br />";
																	
									$canalesEPG = $ik->listar_canales(TABLA_EPG_DB_IKON);
									$canalesExtraEPG = $ik->listar_canales(TABLA_EXTRA_EPG_DB_IKON);
															
									//if(Arreglo::comparar_arreglos($canalesEPG, $canalesExtraEPG)) 
									if(!Arreglo::comparar_arreglos($canalesEPG, $canalesExtraEPG)) {
										echo "10. Comparacion de canales: OK.";
										echo "<br />";

										//print_r($canalesEPG);
										
										/*echo "<br />";
										for($i=0; $i<count($canalesEPG); $i++) {
											echo "Numero canal canalesEPG:".$canalesEPG[$i]['numeroCanal'];
											echo "<br />";
										}*/

										//print_r($canalesExtraEPG);
			
										/*echo "<br />";
										for($i=0; $i<count($canalesExtraEPG); $i++) {
											echo "Numero canal canalesExtraEPG:".$canalesExtraEPG[$i]['numeroCanal'];
											echo "<br />";
										}*/

									}
									else {
										error_log(date("d/m/Y-G:i") . " - ERROR: Las listas de canales no son iguales.\n", 3, LOG_ERRORS);
										enviar_y_mostrar_error();
									}
								}
								else {
									error_log(date("d/m/Y-G:i") . " - ERROR: Se encontraron programas con duracion '00:00'.\n", 3, LOG_ERRORS);
									enviar_y_mostrar_error();
								}
							}
							else {
								enviar_y_mostrar_error();
							}
						}
						else {
							enviar_y_mostrar_error();
						}
					}
					else {
						enviar_y_mostrar_error();		
					}
				}
				else {
					unlink(FTP_NEW_LOCAL_FILE);
					error_log(date("d/m/Y-G:i") . " - ERROR: Los archivos '" .FTP_NEW_LOCAL_FILE . "'' y '" . FTP_LOCAL_FILE . "'' tienen la misma fecha de modificación.\n", 3, LOG_ERRORS);
					enviar_y_mostrar_error();
				}
			}
			else {
				unlink(FTP_NEW_LOCAL_FILE);
				error_log(date("d/m/Y-G:i") . " - ERROR: Los archivos '" .FTP_NEW_LOCAL_FILE . "'' y '" . FTP_LOCAL_FILE . "'' son igualess.\n", 3, LOG_ERRORS);
				enviar_y_mostrar_error();
			}
		}
		else {
			enviar_y_mostrar_error();
		}

		$ik->close_connection();
	}

	
	/*
	function enviar_y_mostrar_error() {
		echo "<br />";
		echo "<br />";
		$error = log::leer_ultimas_n_lineas(1,LOG_ERRORS);
		echo $error;
		Email::gmail($error);
	}
	*/

	echo "<br />";
	echo "<br />";
	echo "<br />";
	echo "<br />";
	echo "FIN";	
	
?>
</pre>