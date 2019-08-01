<pre>

<?php

	ini_set("display_errors", "On");
	error_reporting(E_ALL | E_STRICT);

	echo "hola";

	$mysqli = new mysqli("localhost", "usuario", "nana", "ikon");

	/* verificar la conexión */
	if (mysqli_connect_errno()) {
    	printf("Conexión fallida: %s\n", mysqli_connect_error());
    	exit();
	}


	$t= "00:00:00";
	$query = "SELECT * FROM EPG WHERE duracionPrograma = '" . $t . "' ORDER BY numeroCanal ASC";
	$resultado = $mysqli->query($query);

	while($fila = $resultado->fetch_array( MYSQLI_BOTH )) {
		$rows[] = $fila;
	}

	foreach($rows as $row) {
		echo $row['numeroCanal'];
	}
	
	/* liberar la serie de resultados */
	$resultado->free();

	/* cerrar la conexión */
	$mysqli->close();

	echo "<br />";
	echo "<br />";
	echo "<br />";
	echo "<br />";
	echo "FIN";	
?>

</pre>
