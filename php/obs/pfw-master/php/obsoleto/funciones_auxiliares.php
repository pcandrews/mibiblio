<?php
	
	ini_set("display_errors", "On");
	error_reporting(E_ALL | E_STRICT);

 	header("Content-Type: text/html; charset=UTF-8"); 
	
	//require_once("config.php");
	//require_once("database.php");
	//require_once("functions.php");
	
	/* Funciones XML para EPG */

	/*
	Devuelve el horario final del programa
	*/
	function horaFinalPrograma ($info) {
		//obtiene la duracion del programa
		$duracion = $info["dBoxDur"];
		
		//separa la duracion en $hora y $min
		list($hora,$min) = explode(":",$duracion);
		
		//Obtiene la fecha y hora de la emision
		list($aux,$horaEmision) = explode("-",$info["tBoxStart"]);
		
		//separa el horario de emison en $horaEm y $minEm
		list($horaEm,$minEm) = explode(":",$horaEmision);
		
		//convierte $horaEm y $minEm en tiempo unix
		$horaEmUnix = mktime($horaEm,$minEm,0,0,0,0);
		
		//suma la duracion a la emision para obtener la hora de finalizacion del programa en tiempo unix
		$horaFinalAux = $horaEmUnix+($min*60)+($hora*60*60);
		
		//cambia el tiempo unix a normal
		$horaFinal = date("G:i",$horaFinalAux);
		
		return $horaFinal;
	}
	
	/*
	Devuelve la fecha de emison en tiempo unix
	*/
	function feu ($info) {
		//Obtiene la fecha y hora de la emision
		list($fechaEmision,$aux) = explode("-",$info["tBoxStart"]);
		//separa la fecha en variables		
		list($anio,$mes,$dia) = explode(".",$fechaEmision);
		//devuelve el tiempo unix de las variables obtenidas
		$fechaEmisionUnix = mktime(0,0,0,$mes,$dia,$anio);
		
		return $fechaEmisionUnix;
	}
	
	/*
	Devuelve la hora de emison
	*/
	function horaEmision ($info) {
		//Obtiene la fecha y hora de la emision
		list($aux,$horaEmision) = explode("-",$info["tBoxStart"]);
		//separa solo la informaciond e la hora		
		list($horaE,$minE ) = explode(":",$horaEmision);
		
		return $horaE;
	}

	/*
	Devuelve los minutos de la emison 
	*/
	function minutoEmision ($info) {
		//Obtiene la fecha y hora de la emision
		list($aux,$horaEmision) = explode("-",$info["tBoxStart"]);
		//separa solo la informaciond e la hora		
		list($aux,$minE ) = explode(":",$horaEmision);
		
		return $minE;
	}

	/* Fin Funciones XML */

	/* Funciones Mysql */

	/*
	Muestra la informacion en un formato adecuado para la visualizacion
	Entrada:
	Retorno:
	*/
	function formatearInfoEPG ($info) {
		if(empty($info)) {
			echo "Imposible dar formato, el array esta vacio.";
			echo "<br />";
		}
		else {
			//$canalIni = $info[0]["numeroCanal"];
			echo "<br />";
			echo "<br />";
			//echo "-----------------";
			//echo "Canal: $canalIni";
			//echo "-----------------";
			echo "<br />";
			foreach ($info as $arrayLineas) {
				foreach ($arrayLineas as $linea){
					echo $linea;
					echo " - ";
				}
				echo "<br />";
			}
		}
	}

	/* Fin funciones Mysql */

	/*
	*/
	function leerUltimaLinea ($archivo) {
		$logErrores = $archivo;
		$filas = file($logErrores);
		$ultimaLinea = $filas[count($filas)-1];

		return $ultimaLinea;
	}  

?>