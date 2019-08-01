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

	class Archivo {

		/**
		*	Compara 2 archivos.
		*	Salida: Devuelve TRUE si son iguales.
		*	Nota: no parece ser correcto. Revisar si se quiere usar.
		*/
		public static function compare_files2 ($file1, $file2) {
			$crc1 = strtoupper(dechex(crc32(file_get_contents($file1))));
			$crc2 = strtoupper(dechex(crc32(file_get_contents($file2))));
			if ($crc1!=$crc2) {
				$control = FALSE;// files not the same
			} else {
				$control = TRUE;// files the same
			}
			return $control;
		}

		/**
		*	Compara 2 archivos.
		*	Nota: no parece ser correcto. Revisar si se quiere usar.
		*/
		public static function compare_files ($file_a, $file_b) {
		    if (filesize($file_a) == filesize($file_b))
		    {
		        $fp_a = fopen($file_a, 'rb');
		        $fp_b = fopen($file_b, 'rb');

		        while (($b = fread($fp_a, 4096)) !== false)
		        {
		            $b_b = fread($fp_b, 4096);
		            if ($b !== $b_b)
		            {
		                fclose($fp_a);
		                fclose($fp_b);
		                return false;
		            }
		        }

		        fclose($fp_a);
		        fclose($fp_b);

		        return true;
		    }

		    return false;
		}

		/**
		*	Compara que dos archivos sean distintos.
		*	Entradas: Los archivos a comparar.
		*	Salida: control=TRUE, si los archivos son diferentes.	
		*/
		public static function son_archivos_distintos ($archA, $archB) {
			$control = FALSE;
			if (md5_file($archA) != md5_file($archB)) {
				$control = TRUE;
			}
			return $control;
		}

		/**
		*	Comprara 2 archivos antiguedad de dos archivos.
		* 	Entradas: Los archivos a comparar.
		*	Salida:control=TRUE, si $archB es mas nuevo y viceversa.
		*/
		public static function encontrar_archivo_mas_nuevo ($archA=FTP_LOCAL_FILE,$archB=FTP_NEW_LOCAL_FILE) {
			$control = FALSE;
			if (filemtime($archA) < filemtime($archB)) {
				$control = TRUE;
			}
			return $control;
		}


		/**
		*	Descomprime el archivo $archivo.
		*	Entradas: nombre del archivo a descomprimir.
		*	Retorno: $control=TRUE, descompresión exitosa. 
		*	Notas: system() — Ejecuta un programa externo y muestra su salida.
		*	exec() - Ejecuta un programa externo. 
		*/
		public static function descomprimir_rar ($archivo, $path) {
			$comando = "unrar e ". $archivo ." " . $path;
			//$ultimaLinea = system( $comando, $retval);
			//echo $comando;
			$ultimaLinea = exec($comando, $retval);
			$control = FALSE;
			if ($ultimaLinea) {
				$control = TRUE;
			}
			else {
				error_log(date("d/m/Y-G:i") . "-ERROR: Imposible extraccion del contenido de {$path}/{$archivo}'.\n", 3, LOG_ERRORS);
				$control = FALSE;
			}
			return $control;
		}

		/**
		*	Controla que en archivo XML sea valido. 
		*	Entradas: Nombre archivo XML.
		*	Retorno: $control=TRUE, si el archivo XML es valido.
		*/
		public static function XML_valido ($archivoXML) {
			$prev = libxml_use_internal_errors(true);
			$control = true;
			
			try {
				new SimpleXMLElement($archivoXML, 0, true);
			} 
			catch(Exception $e) {
				$control = FALSE;
			}
			if(count(libxml_get_errors()) > 0) {
				// There has been XML errors
				$control = FALSE;
				error_log(date("d/m/Y-G:i") . " - ERROR: Se encontraron errores en {$archivoXML}'.\n", 3, LOG_ERRORS);
			}
			else {
				$control = TRUE;
			}
			
			// Tidy up.
			libxml_clear_errors();
			libxml_use_internal_errors($prev);

			return $control;
		}

		/**
		*
		*/
	    public function obtener_direcciones() {
	        $row=0;
	        if (($handle = fopen("q.csv", "r")) !== FALSE) {
				$lonlat;
				$fp = fopen("textfile3.txt","a");
				fwrite($fp, "lat\tlon\ttitle\tdescription\ticon\ticonSize\ticonOffset" . PHP_EOL);         
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				    $num = count($data);
				    //echo "<p> $num fields in line $row: <br /></p>\n";
				    $row++; 		                
				    if($row>1) {
				        $dir = str_replace(" ", "+", "$data[4]+$data[5]");
				        $lonlat = enviar_a_gmaps($dir);
				        //sleep(5);
				        //echo $dir."<br />";
				        //fwrite($fp, "$lonlat\t$data[2], $data[3]\tDir: $data[4] $data[5]\thttp://www.openlayers.org/dev/img/marker.png\t24,24\t0,-24" . PHP_EOL);
				        //a veces no se ve la image de la casa, esto es por un problema con el cache, al limpiar el cache del firefox se soluciona el problema.
				        fwrite($fp, "$lonlat\t$data[2], $data[3]\tDir: $data[4] $data[5]\tcasa.png\t24,24\t0,-24" . PHP_EOL);
				    }
				}
				fclose($handle);
				fclose($fp);
				$handle = fopen("http://www.example.com/", "r");
			}
	    }
	}

?>