<?php
	
	/** 
	*	Dependecias:
	*		Ninguna.
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

	class MapaAbonados extends Mapa {

		private	$registros = array(	'usuario',
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

    	/*** Obtener info de la base de datos ***/
    	/* 
    		Hacer una mejora, estos todos son indenticos, hacer 1 para todos ellos.
    		Quizas hacer la mejora en database.php fetch array.

    	*/

	    /**
	    *
	    */
	    public function obtener_direcciones_tabla ($tabla="abonados") { 
			$i=0;
			$info = array();
			$resultado = $this->query("SELECT id, calle, nroCalle FROM {$tabla} ORDER by id");
			while($fila = $resultado->fetch_array( MYSQLI_BOTH )) {
				$info[$i] = array(	"id" => $fila["id"],
									"calle" => $fila["calle"], 
									"nroCalle" => $fila["nroCalle"]
								);				
				$i++;
				// cuando intento hacerlo desde aqui da error, quien el while es damasiado rapido??
				//echo $i. ". ";
				//$dir = str_replace(" ", "+", $info[$i]['calle'] . "+" . $info[$i]["nroCalle"]);
				//$this->enviar_a_gmaps3($dir);
			}
			return $info;
		}

		/**
		*
		*/
		public function obtener_coord_gps_tabla ($tabla="abonados") {
			$i=0;
			$info = array();
			$resultado = $this->query("SELECT lon, lat, id FROM {$tabla} ORDER by id");
			while($fila = $resultado->fetch_array( MYSQLI_BOTH )) {
				$info[$i] = array(	"id" => $fila["id"],
									"lon" => $fila["lon"], 
									"lat" => $fila["lat"]
								);				
				$i++;
				// cuando intento hacerlo desde aqui da error, quien el while es damasiado rapido??
				//echo $i. ". ";
				//$dir = str_replace(" ", "+", $info[$i]['calle'] . "+" . $info[$i]["nroCalle"]);
				//$this->enviar_a_gmaps3($dir);
			}
			return $info;
		}

		/*** Fin obtener info base de datos ***/

	    /**
	    *
	    */
	    public function cargar_tabla_abonados () {
			$nombreArchivoCSV = DATA_PATH.DS."abonados.csv";
			$nombreTabla = TABLA_ABONADOS_DB_MAPA_ABONADOS;
			$registros = $this->registros;
			$this->csv_a_mysql($nombreArchivoCSV, $nombreTabla, $registros);
			$this->cargar_bd_columnas_restantes();
		}

    	/**
    	*	Carga la informacion que no se encuentra en el archivo archivo.csv
    	*/
    	public function cargar_bd_columnas_restantes () {

    		/*
    		Test

    		$columnas[0] = 'lon';
    		$columnas[1] = 'lat';
    		$columnas[2] = 'tv';
    		$columnas[3] = 'internet';
    		$columnas[4] = 'comentarios';

    		$lonlat['lon'] = 1111.11;
    		$lonlat['lat'] = 2222.22;
    		$servicios['tv'] = "'premium'";
    		$servicios['internet']= "'full'";
    		$comentarios = "'comentario'";

    		$valores[0] = $lonlat['lon'];
    		$valores[1] = $lonlat['lat'];
    		$valores[2] = $servicios['tv'];
    		$valores[3]	= $servicios['internet'];
    		$valores[4]	= $comentarios;

    		$id=1;

    		$this->update_column(TABLA_ABONADOS_DB_MAPA_ABONADOS,$columnas,$valores,$id);				
    	
			*/

			$columnas = array();
			$servicios = array();

    		$columnas[0] = 'lon';
    		$columnas[1] = 'lat';
    		$columnas[2] = 'tv';
    		$columnas[3] = 'internet';
    		$columnas[4] = 'comentarios';

    		$servicios['tv'] = "'premium'";
    		$servicios['internet']= "'full'";
    		$comentarios = "'comentario'";

    		$valores[2] = $servicios['tv'];
    		$valores[3]	= $servicios['internet'];
    		$valores[4]	= $comentarios;

    		$info = $this->obtener_direcciones_tabla();    		
    		for($i=0; $i<count($info); $i++) {
    			$id = $info[$i]['id'];
				$dir = str_replace(" ", "+", $info[$i]['calle'] . "+" . $info[$i]["nroCalle"]);
				$lonlat = $this->enviar_a_gmaps3($dir);
				$valores[0] = $lonlat['lon'];
    			$valores[1] = $lonlat['lat'];				
				$this->update_column(TABLA_ABONADOS_DB_MAPA_ABONADOS,$columnas,$valores,$id);
    		}
    	}

		/**
	    *	Nota: 
	    *		Hay un limite diario de la contidad de consultas que se pueden hacer a google. Ej:
	    *		Consulta: http://maps.googleapis.com/maps/api/geocode/xml?address=RIVADAVIA+640
	    *		Respuesta: You have exceeded your daily request quota for this API.
	    */
	    private function enviar_a_gmaps3 ($dir) {
	        unset($coord);
	        unset($xml);			

	        //Para probar cuando se excede la cuota.
	       	//$lonlat["lon"] = "33333.33333";
	        //$lonlat["lat"] = "77777.55555";
	        
	        //info de google
			$xml = file_get_contents("http://maps.googleapis.com/maps/api/geocode/xml?address={$dir},+San+Miguel+de+Tucum%C3%A1n,+Tucum%C3%A1n&sensor=true_or_false");
	        $coord = new SimpleXMLElement($xml);
	       	$lonlat["lon"] = $coord->result->geometry->location->lng;
	        $lonlat["lat"] = $coord->result->geometry->location->lat;

	        /*
			echo "URL: http://maps.googleapis.com/maps/api/geocode/xml?address{$dir},+San+Miguel+de+Tucum%C3%A1n,+Tucum%C3%A1n&sensor=true_or_false";
	        echo "</br>";
	        echo "Lat: " . $coord->result->geometry->location->lat;
	        echo "</br>";
	        echo "Lon: " . $coord->result->geometry->location->lng;
	        echo "</br>";
	        echo "Dir: " . $dir;
	        echo "</br>";
	        echo "$lonlat["lon"]: " . $lonlat["lon"];
	        echo "$lonlat["lat"]: " . $lonlat["lat"];
	        echo "</br>";
	        echo "</br>";
	        echo "</br>";
	        */
	        
	        unset($coord);
	        unset($xml);

	        return $lonlat;
	    }


































































		/***********************************************/
		/*				  obsoleto 					   */
		/***********************************************/

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


	    /**
	    *
	    */
	    private function enviar_a_gmaps ($dir) {

	        unset($lat);
	        unset($xml);


	        //$xml = file_get_contents('http://maps.googleapis.com/maps/api/geocode/xml?address=$dir,+San+Miguel+de+Tucum%C3%A1n,+Tucum%C3%A1n&sensor=true_or_false');
	        //$xml = file_get_contents('http://maps.googleapis.com/maps/api/geocode/xml?address=RIVADAVIA+640,+San+Miguel+de+Tucum%C3%A1n,+Tucum%C3%A1n&sensor=true_or_false');
	        $xml = file_get_contents("http://maps.googleapis.com/maps/api/geocode/xml?address=" . $dir  . ",+San+Miguel+de+Tucum%C3%A1n,+Tucum%C3%A1n&sensor=true_or_false");
	        echo "http://maps.googleapis.com/maps/api/geocode/xml?address=" . $dir  . ",+San+Miguel+de+Tucum%C3%A1n,+Tucum%C3%A1n&sensor=true_or_false";

	        //file_put_contents('file.xml', $xml);
	        $lat = new SimpleXMLElement($xml);
	       /* echo "</br>";
	        echo "</br>";
	        echo "Lat: " . $lat->result->geometry->location->lat;
	        echo " - Long: " . $lat->result->geometry->location->lng;
	        echo " - Dir: " . $dir;
	        echo "</br>";
	        echo "</br>";
	        echo "</br>";
	        echo "</br>";*/

	        $lonlat = $lat->result->geometry->location->lat."\t".$lat->result->geometry->location->lng;
	        unset($lat);
	        unset($xml);

	        return $lonlat;
	    }

	    /**
	    *
	    */
	    private function enviar_a_gmaps2 ($dir) {

	        unset($lat);
	        unset($xml);

	        //$xml = file_get_contents('http://maps.googleapis.com/maps/api/geocode/xml?address=$dir,+San+Miguel+de+Tucum%C3%A1n,+Tucum%C3%A1n&sensor=true_or_false');
	        //$xml = file_get_contents('http://maps.googleapis.com/maps/api/geocode/xml?address=RIVADAVIA+640,+San+Miguel+de+Tucum%C3%A1n,+Tucum%C3%A1n&sensor=true_or_false');
	        $xml = file_get_contents("http://maps.googleapis.com/maps/api/geocode/xml?address=" . $dir  . ",+San+Miguel+de+Tucum%C3%A1n,+Tucum%C3%A1n&sensor=true_or_false");
	        echo "http://maps.googleapis.com/maps/api/geocode/xml?address=" . $dir  . ",+San+Miguel+de+Tucum%C3%A1n,+Tucum%C3%A1n&sensor=true_or_false";

	        //file_put_contents('file.xml', $xml);
	        $lat = new SimpleXMLElement($xml);
	        echo "</br>";
	        echo "</br>";
	        echo "Lat: " . $lat->result->geometry->location->lat;
	        echo " - Long: " . $lat->result->geometry->location->lng;
	        echo " - Dir: " . $dir;
	        echo "</br>";
	        echo "</br>";
	        echo "</br>";
	        echo "</br>";

	        $lonlat = array($lat->result->geometry->location->lng,$lat->result->geometry->location->lat);
	        unset($lat);
	        unset($xml);

	        return $lonlat;
	    }
	}

?>