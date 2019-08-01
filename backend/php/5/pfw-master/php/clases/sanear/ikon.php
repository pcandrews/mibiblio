<?php
	
	/** 
	*	Dependecias: 
	*		config.h
	*		database.h
	*		ftp.h
	*		archivo.h
	*
	*	Descripcion: 
	*/

	header('Content-Type: text/html; charset=UTF-8'); 
	ini_set("display_errors", "On");
	error_reporting(E_ALL | E_STRICT);
 	header("Content-Type: text/html; charset=UTF-8");
 	date_default_timezone_set('America/Argentina/Tucuman');
 	setlocale(LC_ALL, 'es-AR');

	require_once(LIB_PATH.DS."config/config.php");

	class Ikon extends MysqlDatabase {

		private $registrosEPG = array(	'usuario',
			'exUsuario',
			'apellido',
			'nombre',
			'calle ',
			'nroCalle' ,
			'direccion' ,
			'municipio ',
			'zona' ,
			'telefono',
			'barrio');

		private $registrosExtraEPG = array ( 'numeroCanal', 
			'nombreCanal', 
			'webCanal', 
			'webProgramacionCanal', 
			'infoCanal');

		public function get_registrosEPG() {
			return $this->registrosEPG;
		}

		/**
		*	PHP magic method.
		*/
 		public function __get($property) {
    		if (property_exists($this, $property)) {
      			return $this->$property;
    		}
  		}

		/**
		*	PHP magic method.
		*/
  		public function __set($property, $value) {
    		if (property_exists($this, $property)) {
      			$this->$property = $value;
    		}
    	}

		/**
		*	La tabla por defecto es TABLA_EPG_DB_IKON.
		*	Pero puede ser introducida otra.
		*	Entradas: nombre del archivo xml, nombre de la tabla, registrosEPG.
		*	Salidas: $control = TRUE si las trasnferencia fue exitosa.
		*/
		public function epg_xml_a_mysql ($nombreArchivoXml, $nombreTabla, $registrosEPG)  {			
			$control = FALSE;

			if (file_exists($nombreArchivoXml)) {
				
				$xml = simplexml_load_file($nombreArchivoXml);
				/*
				echo "Llenando ikon.EPG";
				echo "<br />";
				*/

				foreach ($xml->EventSchedule as $canales) {			
			   		foreach ($canales->Event as $info) {

				    	$horaE = $this->hora_emision($info);
					  	$minE = $this->minuto_emision($info);					
					   	$horaFinal = $this->hora_final_programa($info);	
					
						$numeroCanal = $canales["sService"]; 
						$tituloPrograma = $info["sTitle"];
						//$fechaEmision = $info["tBoxStart"];
						$fechaEmision = date("Y-m-d", $this->fecha_emision_unix($info));
						$horaInicioPrograma = $horaE.":".$minE;
						$horaFinPrograma = $horaFinal;
						$duracionPrograma = $info["dBoxDur"];
						$generoPrograma = $info->ShortDescriptor;
						$sinopsisPrograma = $info->ExtendedDescriptor;
						$uid = $info["uId"];

			   			$sql = "INSERT INTO {$nombreTabla} (
							numeroCanal,
							tituloPrograma, 
							fechaEmision, 
							horaInicioPrograma,
							horaFinPrograma,
							duracionPrograma,
							generoPrograma, 
							sinopsisPrograma,
							uid
							)
							VALUES (
							'$numeroCanal',
							'$tituloPrograma',
							'$fechaEmision',
							'$horaInicioPrograma',
							'$horaFinPrograma',
							'$duracionPrograma',
							'$generoPrograma',
							'$sinopsisPrograma',
							'$uid'
						)";
						
						$control = $this->query($sql);

						/*
						echo "XML a mysql Correcto";
						echo "<br />";
						*/
			   		}
			   	}
			}
			else {
				error_log(date("d/m/Y-G:i") . " - ERROR: No existe epg.xml.\n", 3, LOG_ERRORS);
			}
			
			return $control;
		}

		/**
		*	Devuelve el horario final del programa
		*/
		private function hora_final_programa ($info) {
			//obtiene la duracion del programa
			$duracion = $info["dBoxDur"];
			
			//separa la duracion en $hora y $min
			list($hora,$min) = explode(":",$duracion);
			
			//Obtiene la fecha y hora de la emision
			list($aux,$horaEmision) = explode("-",$info["tBoxStart"]);
			
			//separa el horario de emison en $horaEm y $minEm
			list($horaEm,$minEm) = explode(":",$horaEmision);
			
			//convierte $horaEm y $minEm en tiempo unix
			$horaEmUnix = mktime($horaEm,$minEm,0,0,0,0);
			
			//suma la duracion a la emision para obtener la hora de finalizacion del programa en tiempo unix
			$horaFinalAux = $horaEmUnix+($min*60)+($hora*60*60);
			
			//cambia el tiempo unix a normal
			$horaFinal = date("G:i",$horaFinalAux);
			
			return $horaFinal;
		}
		
		/*
		* Devuelve la fecha de emison en tiempo unix
		*/
		private function fecha_emision_unix ($info) {
			//Obtiene la fecha y hora de la emision
			list($fechaEmision,$aux) = explode("-",$info["tBoxStart"]);
			//separa la fecha en variables		
			list($anio,$mes,$dia) = explode(".",$fechaEmision);
			//devuelve el tiempo unix de las variables obtenidas
			$fechaEmisionU = mktime(0,0,0,$mes,$dia,$anio);
			
			return $fechaEmisionU;
		}
		
		/*
		*	Devuelve la hora de emison
		*/
		private function hora_emision ($info) {
			//Obtiene la fecha y hora de la emision
			list($aux,$horaEmision) = explode("-",$info["tBoxStart"]);
			//separa solo la informaciond e la hora		
			list($horaE,$minE ) = explode(":",$horaEmision);
			
			return $horaE;
		}

		/**
		*	Devuelve los minutos de la emison 
		*/
		private function minuto_emision ($info) {
			//Obtiene la fecha y hora de la emision
			list($aux,$horaEmision) = explode("-",$info["tBoxStart"]);
			//separa solo la informaciond e la hora		
			list($aux,$minE ) = explode(":",$horaEmision);
			
			return $minE;
		}

		/**
		*	Verifca si existe o no una duracion dada en la base de datos.
		*	Entradas: Duracion a buscar.
		*	Retorno: $control=TRUE, si encuentra un valor con la duracion pasada.
		*/
		public function verificar_duracion ($duracion) {
			$info = $this->buscar_por_duracion($duracion);

			if (empty($info)){
				$control = FALSE;
			}
			else {
				$control = TRUE;
				error_log(date("d/m/Y-G:i") . "-ERROR: Encontrados programas con duracion '00:00'.\n", 3, LOG_ERRORS);
				
				echo "<br />";
				for($i=0; $i<count($info); $i++) {
					echo "Numero canal:".$info[$i]['numeroCanal']." - ";
					echo "Titulo programa: ".$info[$i]["tituloPrograma"]." - ";
					echo "Hora inicio: ".$info[$i]["horaInicioPrograma"]." - ";
					echo "Hora fin: ".$info[$i]["horaFinPrograma"]." - ";
					echo "Duracion: ".$info[$i]["duracionPrograma"];
					echo "<br />";
				}			
			}
			return $control;
		}

		/**
		*	Busca las filas con una duracion dada.
		*	Entrada: $t, la duracion.
		*	Salida: array, las filas encontradas.
		*/
		public function buscar_por_duracion ($t) {
			$i=0;
			$info = array();
			$query = "SELECT * FROM EPG WHERE duracionPrograma = '" . $t . "' ORDER BY numeroCanal ASC";
			$resultado = $this->query($query);
			while($fila = $resultado->fetch_array( MYSQLI_BOTH )) {
				$info[$i] = array(	"numeroCanal" => $fila["numeroCanal"], 
									"tituloPrograma" => $fila["tituloPrograma"],
									"fechaEmision" => $fila["fechaEmision"],
									"horaInicioPrograma" => $fila["horaInicioPrograma"],
									"horaFinPrograma" => $fila["horaFinPrograma"],
									"duracionPrograma" => $fila["duracionPrograma"]
								);				
				$i++;
			}
			return $info;
		}

		/**
		*	Lista todos los canales de la base de datos.
		*	Salida: array, la lista de canales.
		*/
		public function listar_canales($tabla) {
			$i = 0;
			$numeros_de_canales = array();
			$sql = "SELECT DISTINCT numeroCanal FROM " . $tabla . " ORDER BY numeroCanal ASC";
			$resultado = $this->query($sql);		
			while($fila = $resultado->fetch_array( MYSQLI_BOTH )) {
				$numeros_de_canales[$i]=array(	"numeroCanal" => $fila["numeroCanal"]);
				$i++;
			}			
			return $numeros_de_canales;
		}

		/**
		*	Compara las listas de canales de EPG y Extra_EPG
		*	Entradas:ninguna.
		*	Salidas:$dill=diferencia entre arrays
		*/
		/*private function comparar_listas_canales () {
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
		}*/

		/**
		*
		*/
		public function cargar_bd_extra_epg () {
			$nombreArchivoCSV = DATA_PATH.DS."extraEPG.csv";
			$nombreTabla = TABLA_EXTRA_EPG_DB_IKON;
			$registros = $this->registrosExtraEPG;
			$this->csv_a_mysql($nombreArchivoCSV, $nombreTabla, $registros);
		}

		/**
		*
		*/
		public function cargar_bd_epg () {
			
			//$ik = new Ikon(DB_IKON);

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

								//if($this->epg_xml_a_mysql(DATA_PATH.DS."epg.xml", TABLA_EPG_DB_IKON, $this->get_registrosEPG())) { <- debe ir este
								if($this->epg_xml_a_mysql(DATA_PATH.DS."epgMuyCorto.xml", TABLA_EPG_DB_IKON, $this->registrosEPG)) {
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

									//if(!$this->verificar_duracion("00:00:00")) { <- debe ir este
									if(!$this->verificar_duracion("00:00:00")) {
										echo "09. No hay programas con duracion '00:00': OK.";
										echo "<br />";
																		
										$canalesEPG = $this->listar_canales(TABLA_EPG_DB_IKON);
										$canalesExtraEPG = $this->listar_canales(TABLA_EXTRA_EPG_DB_IKON);
																
										//if(arreglo::comparar_arreglos($canalesEPG, $canalesExtraEPG)) 
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
											echo log::leer_ultimas_n_lineas(1,LOG_ERRORS);
										}
									}
									else {
										error_log(date("d/m/Y-G:i") . " - ERROR: Se encontraron programas con duracion '00:00'.\n", 3, LOG_ERRORS);
										echo log::leer_ultimas_n_lineas(1,LOG_ERRORS);
									}
								}
								else {
									echo log::leer_ultimas_n_lineas(1,LOG_ERRORS);
								}
							}
							else {
								echo log::leer_ultimas_n_lineas(1,LOG_ERRORS);
							}
						}
						else {
							echo log::leer_ultimas_n_lineas(1,LOG_ERRORS);
						}
					}
					else {
						unlink(FTP_NEW_LOCAL_FILE);
						error_log(date("d/m/Y-G:i") . " - ERROR: Los archivos '" .FTP_NEW_LOCAL_FILE . "'' y '" . FTP_LOCAL_FILE . "'' tienen la misma fecha de modificación.\n", 3, LOG_ERRORS);
						echo log::leer_ultimas_n_lineas(1,LOG_ERRORS);
					}
				}
				else {
					unlink(FTP_NEW_LOCAL_FILE);
					error_log(date("d/m/Y-G:i") . " - ERROR: Los archivos '" .FTP_NEW_LOCAL_FILE . "'' y '" . FTP_LOCAL_FILE . "'' son igualess.\n", 3, LOG_ERRORS);
					echo log::leer_ultimas_n_lineas(1,LOG_ERRORS);
				}
			}
			else {
				echo log::leer_ultimas_n_lineas(1,LOG_ERRORS);
			}
		}
	}

?>