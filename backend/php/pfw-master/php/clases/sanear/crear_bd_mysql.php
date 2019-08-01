<?php
	
	/** 
	*	Dependecias: 
	*		config.h
	*	
	*	Descripcion:
	*/

	ini_set("display_errors", "On");
	error_reporting(E_ALL | E_STRICT);

 	header("Content-Type: text/html; charset=UTF-8"); 

	require_once(LIB_PATH.DS."config/config.php");

	/**
	*	CRUD? creat read update delete, 
	*	Mejor control de querys, mirar database.php
	*/
	class CrearBDMysql {

		private $conex;
		private $nombreBD;
		/**
		*
		*/
		function __construct() {
			$this->conectar();
    	}


    	/**
    	*  	Por defecto conecta como root con el servidor, si no les son pasados mas datos.
    	*/
    	public function conectar($servidor=DB_SERVER, $usuario=DB_USERROOT, $contraseña=DB_PASSROOT, $nombreBD="") {
    		echo "<br />";
    		echo "<br />";
    		echo "Servidor: " . $servidor . " - Usuario: " .  $usuario . " - Contraseña: " . $contraseña . " - Base de datos: " . $nombreBD;
    		echo "<br />";
    		echo "<br />";
    		$this->conex = new mysqli($servidor, $usuario, $contraseña, $nombreBD);
			if ($this->conex->connect_errno) {
				echo "<br />";
				echo "<br />";
	    		die( "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);

			}
			else {
				//$this->query ("SET NAMES 'UTF8'"); //Este punto es crucial para tener la informacion correctamente codificada en español
				echo "Conexión establecida.";
				echo "<br />";
				echo $this->conex->host_info;
				echo "<br />";
			}
    	}

    	/**
    	*
    	*/
    	public function cerrar_conexion () {
			$desconexion = $this->conex->close();
			if (!$desconexion) {
    			echo "Fallo al cerrar la conexión con MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    			echo "<br />";
			}
			else {
				echo "Desconexión exitosa";
				echo "<br />";
			}
		}


		/**
		*
		*/
		private function eliminar_usuario() {
			//Reset MySQL
			$sql="REVOKE ALL PRIVILEGES, GRANT OPTION FROM '" . DB_USER ."'@'" .  DB_SERVER . "'";
			//echo "Todos los privilegios de " . DB_USER . " revocados.";
			//echo "<br />";
			$this->conex->query($sql);

			$sql="FLUSH PRIVILEGES";
			//echo "Todos los privilegios de " . DB_USER . " fueron flushed.";
			echo "Todos los privilegios de " . DB_USER . " revocados.";
			echo "<br />";
			$this->conex->query($sql);

			$sql = "DROP USER " . DB_USER. "@" . DB_SERVER;
			echo "Usuario '" . DB_USER . "' eliminado";
			echo "<br />";
			$this->conex->query($sql);
			//
		}

		/**
		*
		*/
		public function crear_usuario($usuarioBD=DB_USER, $passUsuBD=DB_PASS) {
			$this->eliminar_usuario();

			//Creacion usuario
 			$sql = "CREATE USER '{$usuarioBD}' IDENTIFIED BY '{$passUsuBD}'";
 			echo "Usuario '{$usuarioBD}' creado con contraseña {$passUsuBD}";
			echo "<br />";
			$this->conex->query($sql);
			//
		}

		/**
		* 	$nombreBD = string - Nombre de la base de datos a crear.
		*	Crea una base de datos y otorga permisos a si creador.
		*/
		public function crear_bd ($nombreBD) {
			//Creacion BD
			$sql = "CREATE DATABASE IF NOT EXISTS {$nombreBD}";
			$sql .= " DEFAULT CHARACTER SET " . DB_CHARSET;
			$sql .= " DEFAULT COLLATE " . DB_COLLATION; 
			echo "Base de datos creada {$nombreBD} ";
			echo "<br />";
			$this->conex->query($sql);
			//

			//Privilegios usuario
			//$sql = "GRANT ALL PRIVILEGES ON {$nombreBD}.* TO '" . DB_USER ."'@'" .  DB_SERVER . "' IDENTIFIED BY '" . DB_PASS . "'";
			$sql = "GRANT ALL PRIVILEGES ON {$nombreBD}.* TO '" . DB_USER ."'@'" .  DB_SERVER;
			$this->conex->query($sql);
			//

			$sql = "FLUSH PRIVILEGES";
			$this->conex->query($sql);

			echo "Todos los privilegios sobre {$nombreBD} otorgados a '" . DB_USER . "' .";
			echo "<br />";

			//Selecciona base de datos
			$this->conex->select_db($nombreBD);
			//
		}

		/**
		*	$nombreTablas: array - Contiene el nombre de las tablas a crear.
		*	$registrosTabla: array - Contiene los registros de las tablas a crear.
		* 	$engine: tipo de motor, por defecto "InnoBD".
		* 	$auto_increment: auto incremento, por defecto 1.
		*/
		public function crear_tablas ($nombreTablas, $registrosTabla, $engine="InnoDB", $auto_increment=1) {
			for ($i=0; $i <sizeof($nombreTablas); $i++) {
				$sql = "CREATE TABLE IF NOT EXISTS {$nombreTablas[$i]} ( {$registrosTabla[$i]} ) ENGINE = {$engine} AUTO_INCREMENT = {$auto_increment} DEFAULT CHARSET =  ". DB_CHARSET." DEFAULT COLLATE = ". DB_COLLATION;

				//control de query rudimentario, mejorar
				if($this->conex->query($sql) === TRUE) {
					echo "Tabla {$nombreTablas[$i]} creada con exito.";
					echo "<br />";
				}
				else {
					echo "Error al enviar el query: ". $sql .'<br />' . $this->conex->error;
					echo "<br />";
				}
			}
		}

		//public function leer () {}
		//public function modificar () {}

		public function eliminar ($nombreBD) {
			$sql = "DROP DATABASE {$nombreBD}";
			echo "Base de datos {$nombreBD} eliminada.";
			echo "<br />";
			$this->conex->query($sql);
		}

		/**
		*
		*/
		public function resetear_bd_y_usuario() {
			//$bd = new MakeMysqlDatabase();
			//1. Se conecta como root por defecto
			//2. Se elimina la base de datos anterior.
			$this->eliminar(DB_IKON);
			$this->eliminar(DB_MAPA_ABONADOS);
			//3. Se crea el usuario
			$this->crear_usuario();
			//4. Crea base de datos
			$this->crear_bd(DB_IKON);
			$this->crear_bd(DB_MAPA_ABONADOS);
			//5. Cierra la conexion con el root
			$this->cerrar_conexion();
			//6. Se conecta con el usuario creado
			$this->conectar(DB_SERVER, DB_USER, DB_PASS, DB_IKON);
			//7. Se crean las tablas.
			$nombreTablas = array(TABLA_EPG_DB_IKON,TABLA_EXTRA_EPG_DB_IKON);
		 	$registrosTabla = array (RG_EPG,RG_EXTRA_EPG);
			$this->crear_tablas($nombreTablas, $registrosTabla);
			//8. Se cierra la conexion.
			$this->cerrar_conexion();
			// Se repiten los pasos 6,7 y 8.
			$this->conectar(DB_SERVER, DB_USER, DB_PASS, DB_MAPA_ABONADOS);
			$nombreTablas = array(TABLA_ABONADOS_DB_MAPA_ABONADOS);
		 	$registrosTabla = array (RG_ABONADOS);
			$this->crear_tablas($nombreTablas, $registrosTabla);
			$this->cerrar_conexion();
		}
	}

?>