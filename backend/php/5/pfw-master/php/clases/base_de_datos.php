<?php

	/**
	*	Dependecias:
	*		..config/config.php
	*
	*	Descripcion:
	*		Clase para manejo de Mysql.
	*/

	header('Content-Type: text/html; charset=UTF-8');
	ini_set("display_errors", "On");
	error_reporting(E_ALL | E_STRICT);
	header("Content-Type: text/html; charset=UTF-8");
	date_default_timezone_set('America/Argentina/Tucuman');
	setlocale(LC_ALL, 'es-AR');

	require_once(LIB_PATH.DS."config/config.php");


	class BaseDeDatos {
		function __construct($nombreBD="") {
			//echo $nombreBD;
			//echo "a";
			//$this->create_db();
			$this->open_connection($nombreBD);
			$this->magic_quotes_active = get_magic_quotes_gpc();
			$this->real_escape_string_exists = function_exists("mysql_real_escape_string");
		}

		/**
		*
		*/
		public function open_connection ($nombreBD) {
			$this->connection = new mysqli(DB_SERVER, DB_USER, DB_PASS, $nombreBD);
			if ($this->connection->connect_errno) {
				die( "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
			}
			else {
				$this->query ("SET NAMES 'UTF8'"); //Este punto es crucial para tener la informacion correctamente codificada en español
				//echo "Conexión exitosa <br />";
				//echo $this->connection->host_info . "<br />";
			}
		}

		/** 
		 *	PHP cierra todos los archivos y conexiones a bases de datos al final del script. Es una buena practica cerrarlas manualmente, pero no es 
		 * 	indisispensable.
		**/
		public function cerrar_conexion () {
			$desconexion = $this->connection->close();
			if (!$desconexion) {
				error_log(date("d/m/Y-G:i") . " - ERROR: Fallo al cerrar la conexión con MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error."\n", 3, LOG_ERRORS);
				echo log::leer_ultimas_n_lineas(1,LOG_ERRORS);
			}
			else {
				//echo "Desconexión exitosa <br />";
			}
		}

		/**
		 *
		 */
		public function query ($sql) {
			/*echo $sql;
			echo "<br />";
			echo "<br />";
			echo "<br />";
			echo "<br />";*/			
			$this->last_query = $sql;
			//echo $this->last_query;
			$result = $this->connection->query($sql);
			$this->confirm_query($result);
			return $result;
		}

		/**
		 *
		**/
		private function confirm_query ($result_set) {
			//echo "entra";
			if(!$result_set) {
				$output = "<br /><br />";
				$output .= "Fallo al ejecutar SQL query:" . $this->connection->error;
				$output .= "<br /><br />";
				$output .= "Ultimo SQL query: " . $this->last_query; 
				die($output);
			}
			else {
				//echo "SQL query confirmado.";
			}
		}


		/**
		 *	Descripcion:
		 *		Devuelve el contenido de una columna, en un array.
		**/
		public function columna_a_array ($col,$tabla) {
			$consulta = $this->query("SELECT $col FROM $tabla");

			while ($fila = $consulta->fetch_array()) {
				$resultado[] = $fila[$col];
			}

			return $resultado;
		}


		/**
		 *	Descripcion:
		 *		Devuelve el contenido de una fila en un array asociativo.
		**/
		public function fetch_row_x ($resultado) {
			$row = $this->connection->fetch_row($resultado);
			return $row;
		}

		
		/***************************************************************************************************************/
		/***************************************************************************************************************/
		/***************************************************************************************************************/
		/***** Depurar lo que esta arriba de la 3 barras *****/


		/**
		 * 	 Metodo de la clase: $this->query
		 * 	 Metodo externo:  $this->connection->query
		 */
		

		/**
		 * 	Descripcion: Devuelve la consulta en un array. Solo un elemento
		 * 	Entrada: "$res_query", es resultado de efecturar un query.
		 * 	Salida:
		 * 	Notas:
		 * 	Actualizar: Agregar logging.
		 */
		public function fetch_array ($res_query, $flag=MYSQLI_BOTH) {
			$resultado = $res_query->fetch_array($flag);
			return $resultado;
		}


		/**
		 * 	Descripcion: Devuelve TODOS los datos de la consulta en un array.
		 * 	Salida:
		 * 	Notas:
		 * 	Actualizar: Agregar logging.
		 */
		public function fetch_all ($query, $flag=MYSQLI_BOTH) {
			$resultado = $this->connection->query($query);
			$a = $resultado->fetch_all($flag);
			$resultado->free();
			return $a;
		}


		/** 
		 *	Arregla problema con caracteres especiales para mysql.
		 */
		public function escape_string ($s) {
			$s = $this->connection->real_escape_string($s);
			return $s;
		}

	}

?>