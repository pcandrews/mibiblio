<!doctype html>

<head>
<script>
 	<?php require_once("test6.php"); ?>
</script>
<script>
	function obtenerGPS() {
	    var index;
	    var text = "<ul>";
	    var lon = <?php  echo json_encode($lon, JSON_NUMERIC_CHECK); ?>;
	    var lat = <?php  echo json_encode($lat, JSON_NUMERIC_CHECK); ?>;


	    for (index = 0; index < lon.length; index++) {
	        text += "<li>Lon: " + lon[index] + "</li><li>Lat: " + lat[index] + "</li>";

	    }
	    text += "</ul>";
	    document.getElementById("gps").innerHTML = text;
	}
</script>
<body onload="obtenerGPS()">

<button onclick="obtenerGPS()">Coordenadas GPS</button>
<p id="gps" onload="obtenerGPS()" ></p>
</body>
</html>
