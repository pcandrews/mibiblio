<!DOCTYPE html>
<html>
<head>
<style>
table {
    width: 100%;
    border-collapse: collapse;
}

table, td, th {
    border: 1px solid black;
    padding: 5px;
}

th {text-align: left;}
</style>
</head>
<body>

<?php
    
    header('Content-Type: text/html; charset=UTF-8'); 
    ini_set("display_errors", "On");
    error_reporting(E_ALL | E_STRICT);
    header("Content-Type: text/html; charset=UTF-8");
    date_default_timezone_set('America/Argentina/Tucuman');
    setlocale(LC_ALL, 'es-AR');

    require_once("config/config.php");

    
    $q = intval($_GET['q']);

    $m = new MapaAbonados(DB_MAPA_ABONADOS);
    
    $lonlat = $m->obtener_coord_gps_tabla();

    for($i=0; $i<count($lonlat); $i++) {
        echo "saaaadrlon: " . $lonlat[$i]["lon"];
        echo "<br />";
        echo "lat: " . $lonlat[$i]["lat"];
        echo "<br />";
    }
?>
</body>
</html>