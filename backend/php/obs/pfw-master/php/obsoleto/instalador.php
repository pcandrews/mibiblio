<pre>

<?php
	
	/* obsoleto */	

	/*
		1.

		Este debe ser el primer script en correr.
		Este script debe correr solo una vez, durante la instalcion.
		Crea usuario, base de datos y tabla EPG en mysql.
	*/

	ini_set('display_errors', 'On');
	error_reporting(E_ALL | E_STRICT);
	
	require_once("config.php");

	/**
	*
	*/
	function preparar_base_de_datos ($nombreBD,$nombreTablas) {

		$conex = new mysqli(DB_SERVER, DB_USERROOT, DB_PASSROOT, $nombreBD);

		if ($conex->connect_errno == 1049) {
			echo "La base de datos no existe y sera creada: ( {$conex->connect_errno} ) $conex ->connect_error";
			echo "<br />";
			reset_mysql($conex);
			crear_bd($conex, $nombreBD, $nombreTablas);

			$conex->close();
		}
		else
		if ($conex->connect_errno) {
			die( "Fallo al conectar a MySQL: (" . $conex->connect_errno . ") " . $conex ->connect_error);
		}
		else {
			echo "Conexión establecida.";
			echo "<br />";
			reset_mysql($conex);
			crear_bd($conex, $nombreBD, $nombreTablas);

			$conex->close();
    	}
	}

	/*
	*
	*/
	function control_query ($conex,$sql,$accion) {
		if($conex->query($sql) === TRUE) {
			echo $accion;
			echo "<br />";
			echo "<br />";
		}
		else {
			echo "Error al enviar el query: ". $sql .'<br />' . $conex->error;
			echo "<br />";
			echo "<br />";
		}
	}

	/**
	*
	*/
	function reset_mysql ($conex) {
		$sql="REVOKE ALL PRIVILEGES, GRANT OPTION FROM '" . DB_USER ."'@'" .  DB_SERVER . "'";
		$accion = "Todos los privilegios de " . DB_USER . " revocados";
		control_query($conex,$sql,$accion);

		$sql="FLUSH PRIVILEGES";
		$accion = "Todos los privilegios de " . DB_USER . " fueron flushed";
		control_query($conex,$sql,$accion);

		$sql = "DROP USER " . DB_USER. "@" . DB_SERVER;
		$accion = "Usuario '" . DB_USER . "' eliminado";
		control_query($conex,$sql,$accion);
	}

	/**
	*	$conex: conexion con la base de datos.
	*	$nombreBD: nombre de la base de datos.
	*	$nombreTablas: nombres de las tablas de la base de datos.
	*/
	function crear_bd ($conex, $nombreBD, $nombreTablas) {

		for ($i=0; $i <sizeof($nombreTablas); $i++) { 
			$sql = "GRANT ALL PRIVILEGES ON {$nombreBD} ". DB_TABLE . " TO '" . DB_USER ."'@'" .  DB_SERVER . "' IDENTIFIED BY '" . DB_PASS . "'";
			echo "Todos los privilegios sobre $nombreBD ". DB_TABLE . "' otorgados a '" . DB_USER . "' .";
			$conex->query($sql);
		}

		$sql = "DROP DATABASE {$nombreBD}";
		echo "Base de datos {$nombreBD} eliminada.";
		$conex->query($sql);

		$sql = "CREATE DATABASE IF NOT EXISTS {$nombreBD}";
		$sql .= " DEFAULT CHARACTER SET " . DB_CHARSET;
		$sql .= " DEFAULT COLLATE " . DB_COLLATION; 
		echo "Base de datos creada.";
		$conex->query($sql);
	}

	/*
	*
	*/
	function crear_tabla ($nombreBD, $nombreTabla, $registrosTabla) {

		$sql = "CREATE TABLE IF NOT EXISTS {$nombreTabla} ( {$registrosTabla} )
		DEFAULT CHARSET = utf8 
		DEFAULT COLLATE = utf8_spanish_ci";
		echo "Tabla {$nombreTabla} creada.";

		$conex->query($sql);
	}




	function crear_bd_EPG() {	
		$sql = "CREATE TABLE IF NOT EXISTS " . DB_TABLE . " 
		(
			PID INT NOT NULL AUTO_INCREMENT,
			PRIMARY KEY(PID),
			numeroCanal INT,
			tituloPrograma VARCHAR(255),
			fechaEmision DATE NOT NULL DEFAULT '2012/01/01', 
			horaInicioPrograma TIME NOT NULL DEFAULT '00:00:00',
			horaFinPrograma TIME NOT NULL DEFAULT '00:00:00',
			duracionPrograma TIME NOT NULL DEFAULT '00:00:00',
			generoPrograma VARCHAR(255), 
			sinopsisPrograma TEXT,
			uid INT
		)
		DEFAULT CHARSET = utf8 
		DEFAULT COLLATE = utf8_spanish_ci";
		$accion = "Tabla '" . DB_TABLE . "' creada.";
		preparar_base_de_datos($sql, $accion, {$nombreBD});
	
		$sql = "CREATE TABLE IF NOT EXISTS Extra_EPG  
		(
			PID INT NOT NULL AUTO_INCREMENT,
			PRIMARY KEY(PID),
			numeroCanal INT,
			nombreCanal VARCHAR(255),
			webCanal VARCHAR(255), 
			webProgramacionCanal VARCHAR(255),
			infoCanal VARCHAR(255)
		)
		DEFAULT CHARSET = utf8 
		DEFAULT COLLATE = utf8_spanish_ci";
		$accion = "Tabla Extra_EPG creada.";
		preparar_base_de_datos($sql, $accion, {$nombreBD});
	}


	function crear_bd_mapa_clientes() {

		$sql = "CREATE TABLE IF NOT EXISTS " . DB_TABLE . " 
		(
			PID INT NOT NULL AUTO_INCREMENT,
			PRIMARY KEY(PID),
			usuario INT,
			exUsuario INT,
			apellido VARCHAR(255),
			nombre VARCHAR(255),
			calle VARCHAR(255),
			NroCalle INT,
			direccion VARCHAR(255),
			municipio VARCHAR(255),
			zona INT,
			telefono INT,
			barrio INT,
			long FLOAT,
			lat FLOAT,
			tv VARCHAR(255),
			internet VARCHAR(255),
			comentarios TEXT
		)
		DEFAULT CHARSET = utf8 
		DEFAULT COLLATE = utf8_spanish_ci";
		$accion = "Tabla '" . DB_TABLE . "' creada.";
		preparar_base_de_datos($sql, $accion, {$nombreBD});
	}
	
	echo "<br />";
	echo "<br />";
	echo "<br />";
	echo "<br />";
	echo "<br />";
	echo "FIN";

?>

</pre>