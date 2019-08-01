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
}).addTo(map);

/*latlng = L.latLng(-26.78250, -65.23400);
L.circleMarker(latlng, {
    stroke: true,  
    color: 'blue',
    weight:  5,
    opacity: 0.5,
    fill: true,
    fillColor: 'red', 
    fillOpacity: 0.2,
    dashArray: null,
    lineCap: null,     
    lineJoin: null,  
    clickable: true,
    pointerEvents: null,
    className:''   
}).addTo(map).bindPopup("Info");*/


n = 10;
lat = -26.78250;
lng = -65.23400;

for (i = 0; i < n; i++) {

    latlng = L.latLng(lat,lng);

    lat -= 0.00050;

    circulo = new L.circle(latlng, 15, {
        stroke: false,  
        color: 'red',
        weight:  3,
        opacity: 0.7,
        fill: true,
        fillColor: 'red', 
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

n = 10;
lat = -26.78250;
lng = -65.23400;

for (i = 0; i < n; i++) {

    latlng = L.latLng(lat,lng);

    lat -= 0.00050;

    circulo = new L.circle(latlng, 10, {
        stroke: false,  
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

    array.push(circulo);
}


markers.addLayers(array);