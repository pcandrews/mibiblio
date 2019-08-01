<!DOCTYPE html>
<html>
  <head>
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
  <meta charset="utf-8">
  <title>Simple markers</title>
  <style>
    html, body, #map-canvas {
      height: 100%;
      margin: 0px;
      padding: 0px
    }
  </style>
  <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
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
      document.getElementById("map-canvas").innerHTML = text;
    }
  </script>

  <script>

    function initialize() {

      var myLatlng = new google.maps.LatLng(-26.83051, -65.20382);
      var mapOptions = {
        zoom: 15,
        center: myLatlng
      }

      var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

      var marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        title: 'Hello World!'
      });

      /*
        anchorPoint   Point   The offset from the marker's position to the tip of an InfoWindow that has been opened with the marker as anchor.
        animation   Animation   Which animation to play when marker is added to a map.
        attribution   Attribution   Contains all the information needed to identify your application as the source of a save. In this context, 'place' means a business, point of interest or geographic location. attribution must be specified with a place in order to enable a save.
        clickable   boolean   If true, the marker receives mouse and touch events. Default value is true.
        crossOnDrag   boolean   If false, disables cross that appears beneath the marker when dragging. This option is true by default.
        cursor  string  Mouse cursor to show on hover
        draggable   boolean   If true, the marker can be dragged. Default value is false.
        icon  string|Icon|Symbol  Icon for the foreground. If a string is provided, it is treated as though it were an Icon with the string as url.
        map   Map|StreetViewPanorama  Map on which to display Marker.
        opacity   number  The marker's opacity between 0.0 and 1.0.
        optimized   boolean   Optimization renders many markers as a single static element. Optimized rendering is enabled by default. Disable optimized rendering for animated GIFs or PNGs, or when each marker must be rendered as a separate DOM element (advanced usage only).
        place   Place   Place information, used to identify and describe the place associated with this Marker. In this context, 'place' means a business, point of interest or geographic location. To allow a user to save this place, open an info window anchored on this marker. The info window will contain information about the place and an option for the user to save it. Only one of position or place can be specified.
        position  LatLng  Marker position. Required.
        shape   MarkerShape   Image map region definition used for drag/click.
        title   string  Rollover text
        visible   boolean   If true, the marker is visible
        zIndex  number  All markers are displayed on the map in order of their zIndex, with higher values displaying in front of markers with lower values. By default, markers are displayed according to their vertical position on screen, with lower markers appearing in front of markers further up the screen.
      */

      var latx = <?php  echo json_encode($lat, JSON_NUMERIC_CHECK); ?>;
      var lonx = <?php  echo json_encode($lon, JSON_NUMERIC_CHECK); ?>;

      for (i = 0; i < lonx.length; i++) {  
        var myLatlngx = new google.maps.LatLng(latx[i], lonx[i]);
        var marker = new google.maps.Marker({
          position: myLatlngx,
          map: map,
          title: 'Hello World!'
        });
      }

    }

  google.maps.event.addDomListener(window, 'load', initialize);

  </script>

</head>
<body onload="obtenerGPS()">
  <div id="map-canvas"></div>
</body>
</html>