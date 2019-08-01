<?php
    
    header('Content-Type: text/html; charset=UTF-8'); 
    ini_set("display_errors", "On");
    error_reporting(E_ALL | E_STRICT);
    header("Content-Type: text/html; charset=UTF-8");
    date_default_timezone_set('America/Argentina/Tucuman');
    setlocale(LC_ALL, 'es-AR');

    require_once("config/config.php");

    $m = new MapaAbonados(DB_MAPA_ABONADOS);    
    $lonlat = $m->obtener_coord_gps_tabla();
    for($i=0; $i<count($lonlat); $i++) {
        $lon[$i] = $lonlat[$i]["lon"];
        $lat[$i] = $lonlat[$i]["lat"];
    }

?>