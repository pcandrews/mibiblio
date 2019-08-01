<?php

	/* 	
		Descripcion:
			Clase para el manejo de Bases de Datos. 
	*/
	header('Content-Type: text/html; charset=UTF-8');
	ini_set("display_errors", "On");
	error_reporting(E_ALL | E_STRICT);
	header("Content-Type: text/html; charset=UTF-8");
	date_default_timezone_set('America/Argentina/Tucuman');
	setlocale(LC_ALL, 'es-AR');
	
	// Dependecias
	require_once("cfg/config_mibiblio.php");

	class BaseDeDatos {

		protected $connection;
		protected $query;
		public $query_count = 0;
		
		public function __construct($dbhost = 'localhost', $dbuser = 'root', $dbpass = '', $dbname = '', $charset = 'utf8') {
			/*if($dbname == '') {
				$this->connection = new mysqli($dbhost, $dbuser, $dbpass);
			}
			else {
				$this->connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
			}*/

			$this->connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
			if ($this->connection->connect_errno) {
				//die( "Fallo al conectar a MySQL: (" . $this->connection->connect_errno . ") " . $this->connection->connect_error);

				error_log(date("d/m/Y-G:i") . " - ERROR: Fallo al cerrar la conexión con MySQL: (" . $this->connection->connect_errno . ") " . $this->connection->connect_error."\n", 3, LOG_ERRORS);
				
				echo log::leer_ultimas_n_lineas(1,LOG_ERRORS);
			}
			else {
				echo "Conexión exitosa <br />";
				echo $this->connection->host_info . "<br />";
				echo $dbname;
				//$this->magic_quotes_active = get_magic_quotes_gpc();
				//$this->real_escape_string_exists = function_exists("mysql_real_escape_string");
				$this->connection->set_charset($charset);
				//$this->query ("SET NAMES 'UTF8'"); //Este punto es crucial para tener la informacion correctamente codificada en español
			}
		}

		/*
			PHP cierra todos los archivos y conexiones a bases de datos al final del script. Es una buena practica cerrarlas manualmente, pero no es 
			indisispensable.
		*/
		public function cerrar_conexion () {
			$desconexion = $this->connection->close();
			if (!$desconexion) {
				error_log(date("d/m/Y-G:i") . " - ERROR: Fallo al cerrar la conexión con MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error."\n", 3, LOG_ERRORS);
				echo log::leer_ultimas_n_lineas(1,LOG_ERRORS);
			}
			else {
				echo "Desconexión exitosa <br />";
			}
		}


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




		/********************************************************************************/


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






/*
 

de esta voy sacando

class db {

	
    public function query($query) {
		if ($this->query = $this->connection->prepare($query)) {
            if (func_num_args() > 1) {
                $x = func_get_args();
                $args = array_slice($x, 1);
				$types = '';
                $args_ref = array();
                foreach ($args as $k => &$arg) {
					if (is_array($args[$k])) {
						foreach ($args[$k] as $j => &$a) {
							$types .= $this->_gettype($args[$k][$j]);
							$args_ref[] = &$a;
						}
					} else {
	                	$types .= $this->_gettype($args[$k]);
	                    $args_ref[] = &$arg;
					}
                }
				array_unshift($args_ref, $types);
                call_user_func_array(array($this->query, 'bind_param'), $args_ref);
            }
            $this->query->execute();
           	if ($this->query->errno) {
				die('Unable to process MySQL query (check your params) - ' . $this->query->error);
           	}
			$this->query_count++;
        } else {
            die('Unable to prepare statement (check your syntax) - ' . $this->connection->error);
        }
		return $this;
    }

	public function fetchAll() {
	    $params = array();
	    $meta = $this->query->result_metadata();
	    while ($field = $meta->fetch_field()) {
	        $params[] = &$row[$field->name];
	    }
	    call_user_func_array(array($this->query, 'bind_result'), $params);
        $result = array();
        while ($this->query->fetch()) {
            $r = array();
            foreach ($row as $key => $val) {
                $r[$key] = $val;
            }
            $result[] = $r;
        }
        $this->query->close();
		return $result;
	}

	public function fetchArray() {
	    $params = array();
	    $meta = $this->query->result_metadata();
	    while ($field = $meta->fetch_field()) {
	        $params[] = &$row[$field->name];
	    }
	    call_user_func_array(array($this->query, 'bind_result'), $params);
        $result = array();
		while ($this->query->fetch()) {
			foreach ($row as $key => $val) {
				$result[$key] = $val;
			}
		}
        $this->query->close();
		return $result;
	}
	
	public function numRows() {
		$this->query->store_result();
		return $this->query->num_rows;
	}

	public function close() {
		return $this->connection->close();
	}

	public function affectedRows() {
		return $this->query->affected_rows;
	}

	private function _gettype($var) {
	    if(is_string($var)) return 's';
	    if(is_float($var)) return 'd';
	    if(is_int($var)) return 'i';
	    return 'b';
	}

}
*/






/*
 

completa

class db {

    protected $connection;
	protected $query;
	public $query_count = 0;
	
	public function __construct($dbhost = 'localhost', $dbuser = 'root', $dbpass = '', $dbname = '', $charset = 'utf8') {
		$this->connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
		if ($this->connection->connect_error) {
			die('Failed to connect to MySQL - ' . $this->connection->connect_error);
		}
		$this->connection->set_charset($charset);
	}
	
    public function query($query) {
		if ($this->query = $this->connection->prepare($query)) {
            if (func_num_args() > 1) {
                $x = func_get_args();
                $args = array_slice($x, 1);
				$types = '';
                $args_ref = array();
                foreach ($args as $k => &$arg) {
					if (is_array($args[$k])) {
						foreach ($args[$k] as $j => &$a) {
							$types .= $this->_gettype($args[$k][$j]);
							$args_ref[] = &$a;
						}
					} else {
	                	$types .= $this->_gettype($args[$k]);
	                    $args_ref[] = &$arg;
					}
                }
				array_unshift($args_ref, $types);
                call_user_func_array(array($this->query, 'bind_param'), $args_ref);
            }
            $this->query->execute();
           	if ($this->query->errno) {
				die('Unable to process MySQL query (check your params) - ' . $this->query->error);
           	}
			$this->query_count++;
        } else {
            die('Unable to prepare statement (check your syntax) - ' . $this->connection->error);
        }
		return $this;
    }

	public function fetchAll() {
	    $params = array();
	    $meta = $this->query->result_metadata();
	    while ($field = $meta->fetch_field()) {
	        $params[] = &$row[$field->name];
	    }
	    call_user_func_array(array($this->query, 'bind_result'), $params);
        $result = array();
        while ($this->query->fetch()) {
            $r = array();
            foreach ($row as $key => $val) {
                $r[$key] = $val;
            }
            $result[] = $r;
        }
        $this->query->close();
		return $result;
	}

	public function fetchArray() {
	    $params = array();
	    $meta = $this->query->result_metadata();
	    while ($field = $meta->fetch_field()) {
	        $params[] = &$row[$field->name];
	    }
	    call_user_func_array(array($this->query, 'bind_result'), $params);
        $result = array();
		while ($this->query->fetch()) {
			foreach ($row as $key => $val) {
				$result[$key] = $val;
			}
		}
        $this->query->close();
		return $result;
	}
	
	public function numRows() {
		$this->query->store_result();
		return $this->query->num_rows;
	}

	public function close() {
		return $this->connection->close();
	}

	public function affectedRows() {
		return $this->query->affected_rows;
	}

	private function _gettype($var) {
	    if(is_string($var)) return 's';
	    if(is_float($var)) return 'd';
	    if(is_int($var)) return 'i';
	    return 'b';
	}

}
*/

/*
How To Use

Connect to a database:
include 'db.php';

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'example';

$db = new db($dbhost, $dbuser, $dbpass, $dbname);

Fetch a record from a database:
$account = $db->query('SELECT * FROM accounts WHERE username = ? AND password = ?', 'test', 'test')->fetchArray();
echo $account['name'];
Or you could do:

$account = $db->query('SELECT * FROM accounts WHERE username = ? AND password = ?', array('test', 'test'))->fetchArray();
echo $account['name'];

Fetch multiple records from a database:
$accounts = $db->query('SELECT * FROM accounts')->fetchAll();

foreach ($accounts as $account) {
	echo $account['name'] . '<br>';
}

Checking the number of rows:
$accounts = $db->query('SELECT * FROM accounts');
echo $accounts->numRows();

Checking the affected number of rows:
$insert = $db->query('INSERT INTO accounts (username,password,email,name) VALUES (?,?,?,?)', 'test', 'test', 'test@gmail.com', 'Test');
echo $insert->affectedRows();

Close the database:
$db->close();

Checking the total number of queries:
echo $db->query_count;*/

?>