<?php


    // get the q parameter from URL
    $q = $_REQUEST["q"];

    $m = new MapaAbonados(DB_MAPA_ABONADOS);
    
    $lonlat = $m->obtener_coord_gps_tabla();

    $hint = "Hola Mundo!2";


    // Output "no suggestion" if no hint was found or output correct values
    echo $hint;
?> 