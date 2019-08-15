<?php

	header('Content-Type: text/html; charset=UTF-8'); 
	ini_set("display_errors", "On");
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL | E_STRICT);
	header("Content-Type: text/html; charset=UTF-8");
	date_default_timezone_set('America/Argentina/Tucuman');
	setlocale(LC_ALL, 'es-AR');

	// Dependecias
	require_once(__DIR__."/../cfg/config_milib.php");

	class Directorio {

		public $atributo_publico;
		private $atributo_privado;

		function __construct() {}

		/*
			Lee un directorio completo.
		 	Devuelve un array con todas las rutas completas de los los elementos del directorio.
		*/
		public function leer ($dir, &$results = array()) {
			
			/*
				Estas 3 lineas, hacen lo mismo, comparar cual es mas eficiente de los 2 metodos.
			  	$path = realpath($dir);
			  	foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $filename) {
			  		echo "$filename</br>";
			  	}
			*/
			

			/*
			 	Mas de lo mismo
			 
			 	$path = realpath(DIR_ORIGEN);

				$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);
				foreach($objects as $name => $object){
				    echo "$name\n";
				}

				$path = realpath(DIR_ORIGEN);

				This prints a list of all files and directories under $path (including $path ifself). If you want to omit directories, remove the RecursiveIteratorIterator::SELF_FIRST 
			*/


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

		/*
			Lee un directorio completo. Devuelve ruta y nombre de todos los directorios.
			Entrada: 
				$dir (string). Directorio a leer.
				&$results (array).
			Salida: $results (array). Devuelve un array la ruta completa de los elementos "NO directorio" del directorio analizado.
		*/
		public function listar_directorios ($dir, &$results = array()) {
			$files = scandir($dir);

			foreach($files as $key => $value){
				$path = realpath($dir.DIRECTORY_SEPARATOR.$value);
				if(is_dir($path)) {
					if($value != "." && $value != "..") {
						$this->listar_directorios($path, $results);
						$results[] = $path;
					}
				}
			}

			return $results;
		}

		/*
			Lee un directorio completo. Devuelve ruta y nombre de todos los NO directorios.
			Entradas: 
				$dir (string). Directorio a leer.
				&$results (array).
			Salida: $results (array). Devuelve un array la ruta completa de los elementos "NO directorio" del directorio analizado.
		*/
		public function listar_archivos ($dir, &$results = array()) {
			$files = scandir($dir);

			foreach($files as $key => $value){
				$path = realpath($dir.DIRECTORY_SEPARATOR.$value);
				if(!is_dir($path)) {
					$results[] = $path;
				} else if($value != "." && $value != "..") {
					//$this->leer($path, $results);
					$this->listar_archivos($path, $results);
					//$results[] = $path;
				}
			}
			return $results;
		}

		/*
		  	Lista TODOS los no directorios de un directorios, incluidos los que estan dentro de subdirectorios.
		*/
		function lista_interativa($dir, &$results = array()) {
			$files = scandir($dir);

			foreach($files as $key => $value){
				$path = realpath($dir.DIRECTORY_SEPARATOR.$value);
				if(!is_dir($path)) {

					if(substr($value,0,1)!= ".") {
						$results[] = $value;
					}

				} else if($value != "." && $value != "..") {
					$this->lista_interativa($path, $results);
				}
			}
			return $results;
		}




		/**
		 *	Descripcion:
		 *		Copia un directorio en otro.
		 * 	Notas:
		 *		La carpeta origen, no puede contener la carpeta destino. Se produce un bucle infinto.
		**/
		function copiar( $source, $target ) {
			/*echo "<br>";
			echo "<br>";
			echo "Source: {$source}";
			echo "<br>";
			echo "Target: {$target}";
			echo "<br>";
			echo "<br>";
			echo "<br>";*/

			if ( is_dir( $source ) ) {
				/*echo "2";
				echo "<br>";*/
				@mkdir( $target );
				$d = dir( $source );
				while ( FALSE !== ( $entry = $d->read() ) ) {
					/*echo "3";
					echo "<br>";*/
					if ( $entry == '.' || $entry == '..' ) {
						continue;
					}
					$Entry = $source . '/' . $entry;
					/*echo $Entry;
					echo "<br>";*/
					if ( is_dir( $Entry ) ) {
						//echo $target;
						//echo "<br>";
						$this->copiar( $Entry, $target . '/' . $entry );
						//chmod ($target . '/' . $entry  , 0777);
						continue;
						//chmod ($target . '/' . $entry  , 0777);
					}
					/*echo $target;
					echo "<br>";
					echo "{$Entry}, {$target}/{$entry}";
					echo "<br>";*/
					//echo $entry;
					//echo "<br>";
					copy( $Entry, $target . '/' . $entry );
					//chmod ($target . '/' . $entry  , 0777);
				}

				$d->close();
			}else {
				copy( $source, $target );
				//chmod ($target, 0777);
			}
		}


		/**
		 *	Descripcion:
		 * 		Crear un directorio en un una ruta dada.
		 */
		function crear ($path) {
			if (!file_exists($path)) {
				if(!mkdir($path, 0777, true)) {
					//echo "Fallo al crear la directorio {$path}.";
					//echo "<br>";
				}
				//echo "El directorio {$path} fue creado con existo.";
				//echo "<br>";
			}
			else {
				//echo "La directorio {$path} ya existe";
				//echo "<br>";
			}
		}

		/**
		 *	Descripcion:
		 * 		Cambio los permisos de un directorio dado. Los archivos y subdirectorios, tambien son afectados.
		 */
		function permisos ($dir, $permisos) {
			$dirs = $this->leer($dir);
			for($i=0; $i<count($dirs);$i++) {
				//echo "PERMISOS: ".$dirs[$i];
				//echo "<br>";
				chmod ($dirs[$i], $permisos);
			}
			chmod ($dir, $permisos);
		}


		function borrar ($dir) {
			if (PHP_OS === 'Windows') {
				exec("rd /s /q {$dir}");
			}
			else {
				exec("rm -rf {$dir}");
			}
		}


		/***************************************************************************************************************/
		/***************************************************************************************************************/
		/***************************************************************************************************************/

		/***** Depurar lo que esta arriba de la 3 barras *****/


		/**
		 * 	Descripcion:
		 * 		Compara recursivamente, el contenido de los directorios $dir_a y $dir_b.
		 * 	Salida:
		 * 		$iguales: Booleano. 
		 * 		TRUE si los datos iguales.
		 * 		FALSE si los datos son son diferentes.
		 * 	Notas:
		 * 		Usa el comando externo "diff".
		 * 	Actualizar:
		 * 		Agregar logging.
		 *		
		**/
		function comparar ($dir_a, $dir_b) {

			//usar realpath para comprobar

			$control = array();
			$comando = "diff -x '.bash*' -x '.*' -qr {$dir_a} {$dir_b}";
			//$comando = "rsync -rv  --exclude='.bash*' --exclude='.*' --size-only --dry-run {$dir_a} {$dir_b}";

			/**
			 * 	Nota importante: devuelve todos los archivos .csv, pero de UN DIRECTORIO ESPECIFICO.
			 * 	glob es de php 4.
			 *  RecursiveIteratorIterator es de php 5.
			 * 
			 * 	$f = glob("/home/pablo/Proyectos/Web/PFW/mapa_abonados/back_end/uhfapp/csv/*.csv");
			 *  print_r($f);
			 *
			 * 	glob(pattern,flags)
			 *
			 * 	flags
			 *		Banderas válidas:
             *
			 *		GLOB_MARK - Añade un barra a cada directorio devuelto
			 *		GLOB_NOSORT - Devuelve los ficheros tal como aparecen en el directorio (sin ordenar). 
			 *			          Cuando no se emple este indicador, los parámetros se ordenan alfabéticamente
			 *		GLOB_NOCHECK - Devuelve el patrón de búsqueda si no se encontraron ficheros coincidentes
			 *		GLOB_NOESCAPE - Las barras invertidas no escapan meta-caracteres
			 *		GLOB_BRACE - Expande {a,b,c} para coincidir con 'a', 'b', o 'c'
			 *		GLOB_ONLYDIR - Devuelve sólo entradas de directorio que coinciden con el patrón
			 *		GLOB_ERR - Se detiene si se produjeron errores de lectura (como directorios ilegibles), por defecto los  *                 errores son ignorados.
			 *
			 */
			
			
			
			echo $comando;
			echo "<br>";
			echo "<br>";
			
			//resultado es NO cero, si hay un error
			exec($comando, $control, $resultado);
			//print_r($control);
			if (!$resultado) {
			
				echo "Origen: '" . DIR_ORIGEN . "'";
				echo "<br>";
				echo "Destino: '". DIR_DESTINO . "'";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "--- SON IGUALES ---";
			}
			else {

				for($i=0; $i<count($control); $i++) {
					echo $control[$i];
					echo "<br>";
				}
			}

			return $resultado;
		}


		/**
		 * 	
		 * 	Descripcion:
		 * 		Sincroniza recursivamente, el contenido de $dir_origen en $dir_destino.
		 * 	Salida:
		 * 		$resultado es inutil, buscar una salida que sirva para algo.	
		 * 	Notas:
		 * 		Usa el comando externo "rsync".
		 * 	Actualizar:
		 * 		Agregar logging.
		 *		
		 */
		function sincronizar ($dir_origen, $dir_destino) {

			//usar realpath para comprobar


			$control = array();
			$lista = array();
			$comando = "rsync -av --exclude='.bash*' --exclude='.*' {$dir_origen} {$dir_destino}";

			$resultado = exec($comando, $control);
			//print_r($control);

			

			return $resultado;
		}


		/**
		 * 	Descripcion:
		 * 		Devuelve una lista, con los archivos, que contienen el patron $patron,
		 * 		a sicronizar de $dir_origen en $dir_destino.
		 * 	Salida:
		 * 		$lista: array
		 * 	Notas:
		 * 		Usa el comando externo "rsync".
		 * 	Actualizar:
		 * 		Agregar logging.
		 *
		 *
		 *	Agregar el patro a buscar.
		 */
		function lista_sincronizacion ($dir_origen, $dir_destino, $patron) {

			//usar realpath para comprobar
			
			$control = array();
			$lista = array();
			$comando = "rsync -av --exclude='.bash*' --exclude='.*' --dry-run {$dir_origen} {$dir_destino}";

			exec($comando, $control);
			//print_r($control);

			$j=0;
			for($i=1; $i<count($control); $i++) {
				if(preg_match("/".$patron."/", $control[$i])) {
					//echo $control[$i];
					//echo "<br>";
					$lista[$j] = $control[$i];
					$j++;
				}				
			}
		
			return $lista;
		}


		/**
		 * 	Descripcion:
		 * 		Guarda la diferencia de 2 directorios en un tercero.
		 * 	Salida:
		 * 		$sincro: Booleano. 
		 * 		TRUE si la sincronizacion fue exitosa.
		 * 		FALSE si hubo algo problema.
		 * 	Notas:
		 * 		Usa el comando externo "rsync". PROBAR
		 * 	Actualizar:
		 * 		Agregar logging.
		 *		
		**/
		function copiar_diferencia ($dir_primera, $dir_segunda, $dir_tercera) {
			$control = array();
			$sincro = FALSE;
			$comando = "rsync -rqcm --compare-dest={$dir_segunda} {$dir_primera} {$dir_tercera}; find {$dir_tercera} -type d -empty -delete";

			echo $dir_primera;
			echo "<br>";
			echo $dir_segunda;
			echo "<br>";
			echo $dir_tercera;
			echo "<br>";
			echo "<br>";
			echo "<br>";

			exec($comando, $control);

			//print_r($control);
			
			return $sincro;
		}
	}

?>