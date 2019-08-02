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



	<script>

	function showUser(str) {
	    if (str == "") {
	        document.getElementById("txtHint").innerHTML = "";
	        return;
	    } else {
	        if (window.XMLHttpRequest) {
	            // code for IE7+, Firefox, Chrome, Opera, Safari
	            xmlhttp = new XMLHttpRequest();
	        } else {
	            // code for IE6, IE5
	            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	        }
	        xmlhttp.onreadystatechange = function() {
	            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
	                document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
	            }
	        }
	        xmlhttp.open("GET","ajaxTest2.php?q="+str,true);
	        xmlhttp.send();
	    }
	}

	function myFunction() {
	    var index;
	    var text = "<ul>";
	    var lon = <?php  echo json_encode($lon, JSON_NUMERIC_CHECK); ?>;
	    var lat = <?php  echo json_encode($lat, JSON_NUMERIC_CHECK); ?>;


	    for (index = 0; index < lon.length; index++) {
	        text += "<li>Lon: " + lon[index] + "</li><li>Lat: " + lat[index] + "</li>";

	    }
	    text += "</ul>";
	    document.getElementById("demo").innerHTML = text;
	}

</script>
	

	<?php require_once("test6.php"); print_r(json_encode($lat[0]));  ?>

</head>
<body>

<button onclick="myFunction()">Try it</button>

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
	
	<!-- Leaflet bibliteca para OSM -->
	<script src="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script>
	<script src="../js/marcasLeaflet.js"></script>


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