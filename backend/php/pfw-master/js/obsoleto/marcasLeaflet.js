    var array = [];
    var i, lat, lng, n, latlng, map, centroMapa;

    centroMapa = L.latLng(-26.83051, -65.20382);
    zoomMapa = 13;

    map = L.map('map', {
    center: centroMapa,
    zoom: zoomMapa
    });


    L.tileLayer('https://{s}.tiles.mapbox.com/v3/{id}/{z}/{x}/{y}.png', {
    id: 'examples.map-i875mjb7'
    }).addTo(map);*/

    
    L.marker([-26.83051, -65.20382]).addTo(map)
    .bindPopup("<b>Hello world!</b><br />I am a popup.").openPopup();

    L.circle([-26.83051, -65.20382], 500, {
    color: 'red',
    fillColor: '#f03',
    fillOpacity: 0.5
    }).addTo(map).bindPopup("I am a circle.");

    L.polygon([
    [51.509, -0.08],
    [51.503, -0.06],
    [51.51, -0.047]
    ]).addTo(map).bindPopup("I am a polygon.");

    



    


    var popup = L.popup();

    function onMapClick(e) {
        popup
        .setLatLng(e.latlng)
        .setContent("You clicked the map at " + e.latlng.toString())
        .openOn(map);
    }

    map.on('click', onMapClick);
    