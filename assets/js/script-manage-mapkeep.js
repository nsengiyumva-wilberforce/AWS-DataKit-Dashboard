alert($(".map_data").html());

(document).ready(function() {
	var map_data=[];
//JSON.parse($(".map_data").html());
	var zoom = 7;
	// var location = new L.LayerGroup();

	// var project1  = new L.LayerGroup();
	// var project2  = new L.LayerGroup();
	// var project3  = new L.LayerGroup();
	// var project4  = new L.LayerGroup();
	// var project5  = new L.LayerGroup();
	// var project6  = new L.LayerGroup();
	// var project7  = new L.LayerGroup();
	// var project8  = new L.LayerGroup();
	// var project9  = new L.LayerGroup();
	// var project10 = new L.LayerGroup();

	var project1  = new L.markerClusterGroup();
	var project2  = new L.markerClusterGroup();
	var project3  = new L.markerClusterGroup();
	var project4  = new L.markerClusterGroup();
	var project5  = new L.markerClusterGroup();
	var project6  = new L.markerClusterGroup();
	var project7  = new L.markerClusterGroup();
	var project8  = new L.markerClusterGroup();
	var project9  = new L.markerClusterGroup();
	var project10 = new L.markerClusterGroup();

	//BASELAYER
	var mbAttr = 'Map data &copy; <a href="http://openstreetmap.org">openstreetmap</a> contributors, ' +
			'<a href="http://creativecommons.org/licenses/by-sa/2.0/">cc-by-sa</a>, ' +
			'imagery © <a href="http://mapbox.com">mapbox</a>',
		// mbUrl = 'http://{s}.tile.osm.org/{z}/{x}/{y}.png';
		mbUrl = 'https://api.mapbox.com/styles/v1/mapbox/streets-v9/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1Ijoic3Nla29ubyIsImEiOiJjaXI0Zmk4Y2QwMDNvaHptYzNtdWplNXI3In0.E4QcGdi1qdwQVa5yYd3bRw';

	var grayscale   = L.tileLayer(mbUrl);

	//MAP PROPERTIES
	var mymap = L.map('map-large', {
		center: [latitude, longitude],
		zoom: zoom,
		// layers: [grayscale, location],
		layers: [grayscale, project1, project2, project3, project4, project5, project6, project7, project8, project9, project10],
		// layers: [grayscale, markers],
		// zoomControl: false,
		// scrollWheelZoom: false
	});




	// L.control.zoom({'position': 'topleft'}).addTo(mymap);


	// for (var i = 0; i < map_data.length; i++) {
	// 	var obj = map_data[i];
	// 	if (obj.coordinates.lat != undefined && obj.coordinates.lon != undefined) {
	// 		// console.log(obj.coordinates);
	// 		L.marker([obj.coordinates.lat, obj.coordinates.lon]).bindPopup('<b>'+obj.title+'</b><br>'+obj.sub_title).addTo(location);
	// 	}
	// }


	var MarkerIcon = L.Icon.extend({
		options: {
			iconSize:     [32, 40],
			iconAnchor:   [16, 40],
			popupAnchor:  [0, -40]
		}
	});

	var marker1  = new MarkerIcon({iconUrl: marker_dir+'marker-1.png'}),
		marker2  = new MarkerIcon({iconUrl: marker_dir+'marker-2.png'}),
		marker3  = new MarkerIcon({iconUrl: marker_dir+'marker-3.png'}),
		marker4  = new MarkerIcon({iconUrl: marker_dir+'marker-4.png'}),
		marker5  = new MarkerIcon({iconUrl: marker_dir+'marker-5.png'}),
		marker6  = new MarkerIcon({iconUrl: marker_dir+'marker-6.png'}),
		marker7  = new MarkerIcon({iconUrl: marker_dir+'marker-7.png'}),
		marker8  = new MarkerIcon({iconUrl: marker_dir+'marker-8.png'}),
		marker9  = new MarkerIcon({iconUrl: marker_dir+'marker-9.png'}),
		marker10 = new MarkerIcon({iconUrl: marker_dir+'marker-10.png'});


// var markers = L.markerClusterGroup();
// markers.addLayer(L.marker(getRandomLatLng(map)));

	var projectLayers = {};
	// console.log(map_data);
	for (var k = 0; k < map_data.length; k++) {
		var cord_group = map_data[k];
		for (var i = 0; i < cord_group.length; i++) {
			var obj = cord_group[i];
			if (obj.coordinates.lat != undefined && obj.coordinates.lon != undefined) {
				var popupInfo = '<b>'+obj.title+'</b><br>'+obj.sub_title;
				if (obj.project != undefined) {
					popupInfo += '<br>Project: '+obj.project;
				}

				switch(k) {
					case 0:
						L.marker([obj.coordinates.lat, obj.coordinates.lon], {icon: marker1}).bindPopup(popupInfo).addTo(project1);
						if (projectLayers[obj.project] == undefined) projectLayers[obj.project] = project1;
					break;
					case 1:
						L.marker([obj.coordinates.lat, obj.coordinates.lon], {icon: marker2}).bindPopup(popupInfo).addTo(project2);
						if (projectLayers[obj.project] == undefined) projectLayers[obj.project] = project2;
					break;
					case 2:
						L.marker([obj.coordinates.lat, obj.coordinates.lon], {icon: marker3}).bindPopup(popupInfo).addTo(project3);
						if (projectLayers[obj.project] == undefined) projectLayers[obj.project] = project3;
					break;
					case 3:
						L.marker([obj.coordinates.lat, obj.coordinates.lon], {icon: marker4}).bindPopup(popupInfo).addTo(project4);
						if (projectLayers[obj.project] == undefined) projectLayers[obj.project] = project4;
					break;
					case 4:
						L.marker([obj.coordinates.lat, obj.coordinates.lon], {icon: marker5}).bindPopup(popupInfo).addTo(project5);
						if (projectLayers[obj.project] == undefined) projectLayers[obj.project] = project5;
					break;
					case 5:
						L.marker([obj.coordinates.lat, obj.coordinates.lon], {icon: marker6}).bindPopup(popupInfo).addTo(project6);
						if (projectLayers[obj.project] == undefined) projectLayers[obj.project] = project6;
					break;
					case 6:
						L.marker([obj.coordinates.lat, obj.coordinates.lon], {icon: marker7}).bindPopup(popupInfo).addTo(project7);
						if (projectLayers[obj.project] == undefined) projectLayers[obj.project] = project7;
					break;
					case 7:
						L.marker([obj.coordinates.lat, obj.coordinates.lon], {icon: marker8}).bindPopup(popupInfo).addTo(project8);
						if (projectLayers[obj.project] == undefined) projectLayers[obj.project] = project8;
					break;
					case 8:
						L.marker([obj.coordinates.lat, obj.coordinates.lon], {icon: marker9}).bindPopup(popupInfo).addTo(project9);
						if (projectLayers[obj.project] == undefined) projectLayers[obj.project] = project9;
					break;
					case 10:
						L.marker([obj.coordinates.lat, obj.coordinates.lon], {icon: marker10}).bindPopup(popupInfo).addTo(project10);
						if (projectLayers[obj.project] == undefined) projectLayers[obj.project] = project10;
					break;
					default:
					// code block
				}
			}
		}
	}


	// var projectLayers = {
	// 	"Project 1": project1,
	// 	"Project 2": project2,
	// 	"Project 3": project3,
	// 	"Project 4": project4,
	// 	"Project 5": project5,
	// 	// "Project 6": project6,
	// 	// "Project 7": project7,
	// 	// "Project 8": project8,
	// 	// "Project 9": project9,
	// 	// "Project 10": project10
	// }

	L.control.layers(null, projectLayers).addTo(mymap);




});

