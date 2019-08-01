<?php


    ini_set("display_errors", "On");
    error_reporting(E_ALL | E_STRICT);

    header("Content-Type: text/html; charset=UTF-8"); 

    require_once("config.php");
    require_once("database.php");

    /*************************/

    function obtenerDirecciones() {
        $row=0;
        if (($handle = fopen("q.csv", "r")) !== FALSE) {   


                $lonlat;

                $fp = fopen("textfile3.txt","a");

                fwrite($fp, "lat\tlon\ttitle\tdescription\ticon\ticonSize\ticonOffset" . PHP_EOL);          




            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                //echo "<p> $num fields in line $row: <br /></p>\n";
                $row++; 
                
                if($row>1) {
                    $dir = str_replace(" ", "+", "$data[4]+$data[5]");
                    $lonlat=enviarAGmaps($dir);

                    //sleep(5);
                    //echo $dir."<br />";

                    //fwrite($fp, "$lonlat\t$data[2], $data[3]\tDir: $data[4] $data[5]\thttp://www.openlayers.org/dev/img/marker.png\t24,24\t0,-24" . PHP_EOL);
                    
                    //a veces no se ve la image de la casa, esto es por un problema con el cache, al limpiar el cache del firefox se soluciona el problema.
                    fwrite($fp, "$lonlat\t$data[2], $data[3]\tDir: $data[4] $data[5]\tcasa.png\t24,24\t0,-24" . PHP_EOL);
                        



                }
            }
            fclose($handle);
            fclose($fp);

            $handle = fopen("http://www.example.com/", "r");
        }
    }

    /****************/
    function enviarAGmaps($dir) {

        unset($lat);
        unset($xml);


        //$xml = file_get_contents('http://maps.googleapis.com/maps/api/geocode/xml?address=$dir,+San+Miguel+de+Tucum%C3%A1n,+Tucum%C3%A1n&sensor=true_or_false');
        //$xml = file_get_contents('http://maps.googleapis.com/maps/api/geocode/xml?address=RIVADAVIA+640,+San+Miguel+de+Tucum%C3%A1n,+Tucum%C3%A1n&sensor=true_or_false');
        $xml = file_get_contents("http://maps.googleapis.com/maps/api/geocode/xml?address=" . $dir  . ",+San+Miguel+de+Tucum%C3%A1n,+Tucum%C3%A1n&sensor=true_or_false");
        echo "http://maps.googleapis.com/maps/api/geocode/xml?address=" . $dir  . ",+San+Miguel+de+Tucum%C3%A1n,+Tucum%C3%A1n&sensor=true_or_false";

        //file_put_contents('file.xml', $xml);
        $lat = new SimpleXMLElement($xml);
        echo "</br>";
        echo "</br>";
        echo "Lat: " . $lat->result->geometry->location->lat;
        echo " - Long: " . $lat->result->geometry->location->lng;
        echo " - Dir: " . $dir;
        echo "</br>";
        echo "</br>";
        echo "</br>";
        echo "</br>";

        $lonlat = $lat->result->geometry->location->lat."\t".$lat->result->geometry->location->lng;
        unset($lat);
        unset($xml);

        return $lonlat;
    }

    function cvsAMysql() {
        
    }

    function obtenerDirecciones2() {

    }


    function enviarAGmaps2 ($dir) {

        unset($lat);
        unset($xml);

        //$xml = file_get_contents('http://maps.googleapis.com/maps/api/geocode/xml?address=$dir,+San+Miguel+de+Tucum%C3%A1n,+Tucum%C3%A1n&sensor=true_or_false');
        //$xml = file_get_contents('http://maps.googleapis.com/maps/api/geocode/xml?address=RIVADAVIA+640,+San+Miguel+de+Tucum%C3%A1n,+Tucum%C3%A1n&sensor=true_or_false');
        $xml = file_get_contents("http://maps.googleapis.com/maps/api/geocode/xml?address=" . $dir  . ",+San+Miguel+de+Tucum%C3%A1n,+Tucum%C3%A1n&sensor=true_or_false");
        echo "http://maps.googleapis.com/maps/api/geocode/xml?address=" . $dir  . ",+San+Miguel+de+Tucum%C3%A1n,+Tucum%C3%A1n&sensor=true_or_false";

        //file_put_contents('file.xml', $xml);
        $lat = new SimpleXMLElement($xml);
        echo "</br>";
        echo "</br>";
        echo "Lat: " . $lat->result->geometry->location->lat;
        echo " - Long: " . $lat->result->geometry->location->lng;
        echo " - Dir: " . $dir;
        echo "</br>";
        echo "</br>";
        echo "</br>";
        echo "</br>";

        $lonlat = array($lat->result->geometry->location->lng,$lat->result->geometry->location->lat);
        unset($lat);
        unset($xml);

        return $lonlat;
    }

    //obtenerDirecciones();


?> 