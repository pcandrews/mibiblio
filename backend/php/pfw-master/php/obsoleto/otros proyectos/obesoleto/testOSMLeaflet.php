<!doctype html>

<!--
	HTML5 Reset: https://github.com/murtaugh/HTML5-Reset
	Free to use
-->

<!--[if lt IE 7 ]> <html class="ie ie6 ie-lt10 ie-lt9 ie-lt8 ie-lt7 no-js" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie ie7 ie-lt10 ie-lt9 ie-lt8 no-js" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie ie8 ie-lt10 ie-lt9 no-js" lang="en"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie ie9 ie-lt10 no-js" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" lang="es"><!--<![endif]-->
<!-- the "no-js" class is for Modernizr. --> 

<head>
	<meta charset="utf-8">
	
	<!-- Always force latest IE rendering engine (even in intranet) -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	
	<!-- Important stuff for SEO, don't neglect. (And don't dupicate values across your site!) -->
	<title>Mapa de usuarios de CCC</title>
	<meta name="Pablo Cristo" content="" />
	<meta name="description" content="" />
	
	<!-- Don't forget to set your site up: http://google.com/webmasters -->
	<meta name="google-site-verification" content="" />
	
	<!-- Who owns the content of this site? -->
	<meta name="Copyright" content="" />
	
	<!--  Mobile Viewport
	http://j.mp/mobileviewport & http://davidbcalhoun.com/2010/viewport-metatag
	device-width : Occupy full width of the screen in its current orientation
	initial-scale = 1.0 retains dimensions instead of zooming out if page height > device height
	maximum-scale = 1.0 retains dimensions instead of zooming in if page width < device width (wrong for most sites)
	-->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- Use Iconifyer to generate all the favicons and touch icons you need: http://iconifier.net -->
	<link rel="shortcut icon" href="favicon.ico" />
	
	<!-- concatenate and minify for production -->
	<link rel="stylesheet" href="../css/reset.css" />
	<link rel="stylesheet" href="../css/style.css" />

	<!-- -->
	<!-- -->
	<!-- -->
	<!-- Hola de estilos mapa-->
	<link rel="stylesheet" href="../css/estiloLeaflet.css" />
	<!-- Hola de estilos leaflet-->
    <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css" />
	<!-- -->
	<!-- -->
	<!-- -->

	<!-- Lea Verou's Prefix Free, lets you use un-prefixed properties in your CSS files -->
	<script src="../js/libs/prefixfree.min.js"></script>
	
	<!-- This is an un-minified, complete version of Modernizr. 
		 Before you move to production, you should generate a custom build that only has the detects you need. -->
	<script src="../js/libs/modernizr-2.7.1.dev.js"></script>






	<!-- Application-specific meta tags -->
	<!-- Windows 8: see http://msdn.microsoft.com/en-us/library/ie/dn255024%28v=vs.85%29.aspx for details -->
	<meta name="application-name" content="" /> 
	<meta name="msapplication-TileColor" content="" /> 
	<meta name="msapplication-TileImage" content="" />
	<meta name="msapplication-square150x150logo" content="" />
	<meta name="msapplication-square310x310logo" content="" />
	<meta name="msapplication-square70x70logo" content="" />
	<meta name="msapplication-wide310x150logo" content="" />
	<!-- Twitter: see https://dev.twitter.com/docs/cards/types/summary-card for details -->
	<meta name="twitter:card" content="">
	<meta name="twitter:site" content="">
	<meta name="twitter:title" content="">
	<meta name="twitter:description" content="">
	<meta name="twitter:url" content="">
	<!-- Facebook (and some others) use the Open Graph protocol: see http://ogp.me/ for details -->
	<meta property="og:title" content="" />
	<meta property="og:description" content="" />
	<meta property="og:url" content="" />
	<meta property="og:image" content="" />



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

	<div class="wrapper"><!-- not needed? up to you: http://camendesign.com/code/developpeurs_sans_frontieres -->

		<header>		
			<h1>Mapa de Usuarios</h1>	
		</header>
		
		<article>
			<div id="map"></div>
		</article>	    
		
		<footer>		
			<p><small>&copy; Copyright CCC 2014. Todos los derechos reservados.</small></p>		
		</footer>

	</div>

	<button onclick="obtenerGPS()">Coordenadas GPS</button>
	<p id="gps" onload="obtenerGPS()" ></p>

	<!-- Leaflet bibliteca para OSM -->
	<script src="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script>
	

<script>

	var array = [];
	var i, lat, lng, n, latlng, map, centroMapa;
	var index;
    var lonx = <?php  echo json_encode($lon, JSON_NUMERIC_CHECK); ?>;
    var latx = <?php  echo json_encode($lat, JSON_NUMERIC_CHECK); ?>;

	centroMapa = L.latLng(-26.83051, -65.20382);
	zoomMapa = 13;

	map = L.map('map', {
		center: centroMapa,
		zoom: zoomMapa
		});


	L.tileLayer('https://{s}.tiles.mapbox.com/v3/{id}/{z}/{x}/{y}.png', {
		id: 'examples.map-i875mjb7'
	}).addTo(map);

	n = 10;
	lat = -26.78250;
	lng = -65.23400;

	for (i = 0; i < n; i++) {

	    latlng = L.latLng(lat,lng);
	

	    circulo = new L.circle(latlng, 15, {
	        stroke: true,  
	        color: 'green',
	        weight:  3,
	        opacity: 0.7,
	        fill: true,
	        fillColor: 'green', 
	        fillOpacity: 0.7,
	       // dashArray: null,
	       // lineCap: null,     
	        //lineJoin: null,  
	       // clickable: true,
	       // pointerEvents: null,
	       // className:'' 
    	}).addTo(map).bindPopup(i.toString());

	    array.push(circulo);
	}

    for (index = 0; index < lonx.length; index++) {
    	latlng = L.latLng(latx[index],lonx[index]);
        circulo = new L.circle(latlng, 30, {
	        stroke: true,  
	        color: 'green',
	        weight:  2,
	        opacity: 0.7,
	        fill: true,
	        fillColor: 'green', 
	        fillOpacity: 0.7,
	       // dashArray: null,
	       // lineCap: null,     
	        //lineJoin: null,  
	       // clickable: true,
	       // pointerEvents: null,
	       // className:'' 
		}).addTo(map).bindPopup(i.toString());

	    circulo = new L.circle(latlng, 20, {
	        stroke: true,  
	        color: 'black',
	        weight:  2,
	        opacity: 0.7,
	        fill: true,
	        fillColor: 'black', 
	        fillOpacity: 0.7,
	       // dashArray: null,
	       // lineCap: null,     
	        //lineJoin: null,  
	       // clickable: true,
	       // pointerEvents: null,
	       // className:'' 
		}).addTo(map).bindPopup(i.toString());

		array.push(circulo);
	}
   


</script>

	<!-- 

		<script src="../js/marcasLeaflet.js"></script>

	-->


	<!-- Grab Google CDN's jQuery. fall back to local if necessary -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script>window.jQuery || document.write("<script src='assets/js/libs/jquery-1.11.0.min.js'>\x3C/script>")</script>

	<!-- this is where we put our custom functions -->
	<!-- don't forget to concatenate and minify if needed -->
	<script src="../js/functions.js"></script>

	<!-- Asynchronous google analytics; this is the official snippet.
		 Replace UA-XXXXXX-XX with your site's ID and uncomment to enable.
		 
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-XXXXXX-XX', 'auto');
	  ga('send', 'pageview');

	</script>
	-->

</body>
</html>