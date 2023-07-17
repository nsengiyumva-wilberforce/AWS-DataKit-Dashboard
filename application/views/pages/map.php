	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h1 class="h2">Locations</h1>
<!-- 		<div class="btn-toolbar mb-2 mb-md-0">
			<div class="btn-group mr-2">
				<button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
				<button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
			</div>
			<button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
				<i data-feather="calendar"></i>
				This week
			</button>
		</div> -->
	</div>
	<!-- <h3><?= $entry->title ?></h3> -->
<div class="row mb-3">
	<div class="col"><?= $report_title ?></div>
</div>
<input type="hidden" class="form_id" value="<?= $form_id ?>"/>	
	<div class="row mb-5">
		<div class="col">
			<div id="map" style="height: 450px;">
				<p style="text-align: center; margin: 20px;">Oops!!!<br>No map data found<br>

<a href="<?= base_url('maps') ?>">Return to Map List</a></p>
			</div>
		</div>

	</div>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<script>
//var map_data=JSON.parse($(".map_data").html());
//alert(map_data);

function initMap() {
    window.initMap = initMap;
          
        loadDynamicMap();
    }


    function loadDynamicMap()
{

var form_id=$(".form_id").val();
//alert("heheheh");
var map_url="https://dashboard.africawatersolutions.org/aws.api/public/entry/showmaps?form_id="+form_id;
    const myLatLng = { lat:0.3476, lng:32.5825};
    var map = new google.maps.Map(document.getElementById("map"), {
     zoom: 7,
     center: myLatLng,
   });
$.ajax({
  method: "get",
  url: map_url,
  dataType: "json",
  success: function(data){
   var json=data.data;
  //alert(JSON.stringify(json));
       $.each(json, function (key, val) {

//alert(val.coordinates.lat);
        var latitude=parseFloat(val.coordinates.lat);
         var longitude=parseFloat(val.coordinates.lon);
  
 var sub_title=val.sub_title;
        var name=val.title;
        var latlng = { lat:latitude, lng:longitude};

       
            var marker=new google.maps.Marker({
position: latlng,
map,
//label: name,
title:sub_title,
});
      

var contentString="<div style='width:300px;height:200px;'>"+name+"<br/> <br/></div>";
var infowindow = new google.maps.InfoWindow({
content: contentString,
});



marker.addListener("click", () => {
infowindow.open({
anchor: marker,
map,
shouldFocus: false,
});
});
     
        }); 
       
  }
});
}

</script>
<script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDNz8YyPsJ0KzVAtOJpiS8-m9Mx0vwPtsA&callback=initMap&v=weekly"
      defer
    ></script>
