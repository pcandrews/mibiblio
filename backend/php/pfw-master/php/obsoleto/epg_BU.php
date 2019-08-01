<?php
	
	ini_set("display_errors", "On");
	error_reporting(E_ALL | E_STRICT);

 	header("Content-Type: text/html; charset=UTF-8"); 

	require_once("config.php");
	require_once("database.php");


	class EPG extends MysqlDatabase {
		
		/*
		*/
		static function search_by_duration ($t_sup) {
			$base_de_datos = new MysqlDatabase();
			$info = array();
			$i=0;
			
			$sql = "SELECT * FROM EPG WHERE duracionPrograma = '" . $t_sup . "' ORDER BY numeroCanal ASC";
			$resultado = $base_de_datos->query($sql);

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
			
			$base_de_datos->close_connection();
			return $info;
		}
 
		/* 
		Llena la tabla ikon.EPG desde epg.xml

		Entradas: ninguna.

		Retorno: $control = TRUE, si finaliza con exito la carga de datos.	 
		*/



		public function xml_a_mysql ($nombreArchivoXml, $nombreTabla="", $registros="")  {			
			if (file_exists($nombreArchivoXml)) {
				
				$xml = simplexml_load_file($nombreArchivoXml);
				echo "Llenando ikon.EPG";
				echo "<br />";
			}
		
		}


		static function fill_table () {
			$base_de_datos = new MysqlDatabase();
			$control = FALSE;

			if (file_exists("epg.xml")) {	   
				$borrar = "TRUNCATE TABLE EPG";
		   		$base_de_datos->query($borrar);

				$xml = simplexml_load_file("epg.xml");
				
				//echo "Llenando ikon.EPG";
				//echo "<br />";

				foreach ($xml->EventSchedule as $canales) {			
			   		foreach ($canales->Event as $info) {
				   		$horaActual = date("G");
				    	$horaE = horaEmision($info);
					  	$minE = minutoEmision($info);					
					   	$horaFinal = horaFinalPrograma($info);	
					   	$sin = $info->ExtendedDescriptor;
					
						$numeroCanal = $canales["sService"]; 
						$tituloPrograma = $info["sTitle"];
						$fechaEmision = $info["tBoxStart"];
						$horaInicioPrograma = $horaE.":".$minE;
						$horaFinPrograma = $horaFinal;
						$duracionPrograma = $info["dBoxDur"];
						$generoPrograma = $info->ShortDescriptor;
						$sinopsisPrograma = $info->ExtendedDescriptor;
						$uid = $info["uId"];
						
						$sinopsisPrograma = $base_de_datos->escape_value($sinopsisPrograma);

						//echo $sinopsisPrograma;
						//echo "<br />";

						$insertar = "INSERT INTO " . DB_TABLE . " (
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
							
						$base_de_datos->query($insertar);
				 	}
			   	}
				$base_de_datos->close_connection();
			   	//echo "Finalizacion exitosa";
			   	//echo "<br />";
			   	$control = TRUE;
			}
			else {
				error_log(date("d/m/Y-G:i") . "No existe epg.xml", 3, "../logs/errores.log");
				$control = FALSE;
			}

			return $control;
		}

		static function get_info_no_repeat ($valor="numeroCanal", $tabla="")  {
			$base_de_datos = new MysqlDatabase();
			$info = array();
			$i=0;

			if($valor!="numeroCanal") {
				$valor2 = $valor . ", numeroCanal";
				//echo "B";
			} 
			else {
				$valor2 = $valor;
				//echo $valor2;
			}
			
			$sql = "SELECT DISTINCT " . $valor2 . " FROM {$tabla} ORDER BY numeroCanal ASC";
			//echo $sql
			$resultado = $base_de_datos->query($sql);


			if($valor2!=$valor) {
				while($fila = $resultado->fetch_array( MYSQLI_BOTH )) {
					$info[$i] = array (	"numeroCanal" => $fila["numeroCanal"],
										$valor => $fila[$valor]
									);
					$i++;
				}
			}
			else {
				while($fila = $resultado->fetch_array( MYSQLI_BOTH )) {
					$info[$i] = array(	"numeroCanal" => $fila["numeroCanal"]
								);
					$i++;
				}
			}
			
			$base_de_datos->close_connection();
			return $info;
			//falta base de datos con numbres de los canales
		}
	}

?>