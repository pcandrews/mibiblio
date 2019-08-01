<pre>

<?php

	/**
	*/

	header('Content-Type: text/html; charset=UTF-8'); 
	ini_set("display_errors", "On");
	error_reporting(E_ALL | E_STRICT);
	header("Content-Type: text/html; charset=UTF-8");
	date_default_timezone_set('America/Argentina/Tucuman');
	setlocale(LC_ALL, 'es-AR');

	require_once(LIB_PATH.DS."config/config.php");

	class Directorio {

		public $atributo_publico;
		private $atributo_privado;

		function __construct() {}

		/**
		*	Lee un directorio completo.
		* 	Devuelve un array con todos los elementos del directorio.
		*/
		public function leer ($dir, &$results = array()) {
			$files = scandir($dir);

		    foreach($files as $key => $value){
		        $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
		        if(!is_dir($path)) {
		            $results[] = $path;
		        } else if($value != "." && $value != "..") {
		            $this->leer($path, $results);
		            $results[] = $path;
		        }
		    }

		    return $results;
		}

		/**
		*	Lee un directorio completo.
		* 	Devuelve un array solo con los elementos "directorio" del directorio analizado.
		*/
		public function leer_solo_directorios () {
			$files = scandir($dir);

 			foreach($files as $key => $value){
		        $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
		        if(is_dir($path)) {
		        	if($value != "." && $value != "..") {
		            	$this->leer($path, $results);
		            	$results[] = $path;
		        	}
		        }
		    }

	    	return $results;
		}

		/**
		*	Descripcion:
		* 		Lee un directorio completo.
		* 	Entradas:
		*  		$dir (string). Directorio a leer.
		* 	Salida:
		* 		Array. Devuelve un array solo con los elementos "NO directorio" del directorio analizado.
		* 	Notas:
		*/
		public function leer_solo_no_directorios ($dir, &$results = array()) {

			$files = scandir($dir);

 			foreach($files as $key => $value){
		        $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
		        if(!is_dir($path)) {
		            $results[] = $path;
		        } else if($value != "." && $value != "..") {
		            $this->leer($path, $results);
		            //$results[] = $path;
		        }
		    }
	    	return $results;
		}

	}

?>
</pre>
